<?php

namespace Drupal\views_taxonomy_term_name_depth\Plugin\views\argument;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\views\Plugin\views\argument\ArgumentPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Argument handler for taxonomy terms with depth.
 *
 * This handler is actually part of the node table and has some restrictions,
 * because it uses a subquery to find nodes with.
 *
 * @ingroup views_argument_handlers
 *
 * @ViewsArgument("taxonomy_index_name_depth")
 */
class IndexNameDepth extends ArgumentPluginBase implements ContainerFactoryPluginInterface {

  /**
   * @var EntityStorageInterface
   */
  protected $termStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityStorageInterface $termStorage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->termStorage = $termStorage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('entity.manager')->getStorage('taxonomy_term'));
  }

  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['depth'] = array('default' => 0);
    $options['break_phrase'] = array('default' => FALSE);
    $options['use_taxonomy_term_path'] = array('default' => FALSE);

    return $options;
  }

  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['depth'] = array(
      '#type' => 'weight',
      '#title' => $this->t('Depth'),
      '#default_value' => $this->options['depth'],
      '#description' => $this->t('The depth will match nodes tagged with terms in the hierarchy. For example, if you have the term "fruit" and a child term "apple", with a depth of 1 (or higher) then filtering for the term "fruit" will get nodes that are tagged with "apple" as well as "fruit". If negative, the reverse is true; searching for "apple" will also pick up nodes tagged with "fruit" if depth is -1 (or lower).'),
    );

    $form['break_phrase'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Allow multiple values'),
      '#description' => $this->t('If selected, users can enter multiple values in the form of 1+2+3. Due to the number of JOINs it would require, AND will be treated as OR with this filter.'),
      '#default_value' => !empty($this->options['break_phrase']),
    );

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Override defaultActions() to remove summary actions.
   */
  protected function defaultActions($which = NULL) {
    if ($which) {
      if (in_array($which, array('ignore', 'not found', 'empty', 'default'))) {
        return parent::defaultActions($which);
      }
      return;
    }
    $actions = parent::defaultActions();
    unset($actions['summary asc']);
    unset($actions['summary desc']);
    unset($actions['summary asc by count']);
    unset($actions['summary desc by count']);
    return $actions;
  }

  public function query($group_by = FALSE) {
    $this->ensureMyTable();

    $break = static::breakString($this->argument);
	if(count($break->value) > 1) {
		if (!empty($this->options['break_phrase'])) {
		// $break = static::breakString($this->argument);
		if ($break->value === array(-1)) {
			return FALSE;
		}
		$operator = (count($break->value) > 1) ? 'IN' : '=';
		$tids = $break->value;
      }

//      $operator = (count($break->value) > 1) ? 'IN' : '=';
//      $tids = $break->value;
    }
    else {
//      $operator = "=";
      $operator = "IN";
      $tids = $this->argument;
    }
    // Now build the subqueries.
    
    if(is_string($tids)) {
    	// Replaces "-" with space if exist.
		$argument = str_replace('-', ' ', $tids);
      $connection = Database::getConnection();
      $query = $connection->select('taxonomy_term_field_data', 't')
        ->fields('t', array('tid'))
        ->condition('t.name', $argument, '=');
      // Execute the statement
      $executed = $query->execute();
      // Get all the results
      $results = $executed->fetchAll(\PDO::FETCH_OBJ);
      // Iterate results
      $tids = array();
      foreach ($results as $row) {
//        $tids = $row->tid;
        $tids[] = $row->tid;
      }
    } else {
		$term_names = array();
		foreach($tids as $term_name) {
			$term_names[] = str_replace('-', ' ', $term_name);
		}
		$connection = Database::getConnection();
		$query = $connection->select('taxonomy_term_field_data', 't')
			->fields('t', array('tid'))
			->condition('t.name', $term_names, 'IN');
			// Execute the statement
			$executed = $query->execute();
			// Get all the results
			$results = $executed->fetchAll(\PDO::FETCH_OBJ);
			$tids = array();
			foreach ($results as $row) {
				$tids[] = $row->tid;
			}
	}
    
    $subquery = db_select('taxonomy_index', 'tn');
    $subquery->addField('tn', 'nid');
    $where = db_or()->condition('tn.tid', $tids, $operator);
    $last = "tn";

    if ($this->options['depth'] > 0) {
      $subquery->leftJoin('taxonomy_term_hierarchy', 'th', "th.tid = tn.tid");
      $last = "th";
      foreach (range(1, abs($this->options['depth'])) as $count) {
        $subquery->leftJoin('taxonomy_term_hierarchy', "th$count", "$last.parent = th$count.tid");
        $where->condition("th$count.tid", $tids, $operator);
        $last = "th$count";
      }
    }
    elseif ($this->options['depth'] < 0) {
      foreach (range(1, abs($this->options['depth'])) as $count) {
        $subquery->leftJoin('taxonomy_term_hierarchy', "th$count", "$last.tid = th$count.parent");
        $where->condition("th$count.tid", $tids, $operator);
        $last = "th$count";
      }
    }

    $subquery->condition($where);
    $this->query->addWhere(0, "$this->tableAlias.$this->realField", $subquery, 'IN');
  }

  function title() {
    $term = current($this->termStorage->loadByProperties(['name' => str_replace('-', ' ', $this->argument)]));
    if (!empty($term)) {
      return $term->getName();
    }
    // TODO review text
    return $this->t('No name');
  }

}
