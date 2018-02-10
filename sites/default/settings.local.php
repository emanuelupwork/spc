<?php

/**
 * @file
 * Local development override configuration feature.
 *
 * To activate this feature, copy and rename it such that its path plus
 * filename is 'sites/default/settings.local.php'. Then, go to the bottom of
 * 'sites/default/settings.php' and uncomment the commented lines that mention
 * 'settings.local.php'.
 *
 * If you are using a site name in the path, such as 'sites/example.com', copy
 * this file to 'sites/example.com/settings.local.php', and uncomment the lines
 * at the bottom of 'sites/example.com/settings.php'.
 */

/**
 * Assertions.
 *
 * The Drupal project primarily uses runtime assertions to enforce the
 * expectations of the API by failing when incorrect calls are made by code
 * under development.
 *
 * @see http://php.net/assert
 * @see https://www.drupal.org/node/2492225
 *
 * If you are using PHP 7.0 it is strongly recommended that you set
 * zend.assertions=1 in the PHP.ini file (It cannot be changed from .htaccess
 * or runtime) on development machines and to 0 in production.
 *
 * @see https://wiki.php.net/rfc/expectations
 */
assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

/**
 * Enable local development services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

/**
 * Show all error messages, with backtrace information.
 *
 * In case the error level could not be fetched from the database, as for
 * example the database connection failed, we rely only on this value.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;

/**
 * Disable the render cache (this includes the page cache).
 *
 * Note: you should test with the render cache enabled, to ensure the correct
 * cacheability metadata is present. However, in the early stages of
 * development, you may want to disable it.
 *
 * This setting disables the render cache by using the Null cache back-end
 * defined by the development.services.yml file above.
 *
 * Do not use this setting until after the site is installed.
 */
$settings['cache']['bins']['render'] = 'cache.backend.null'; // uncommented to disable cache

/**
 * Disable Dynamic Page Cache.
 *
 * Note: you should test with Dynamic Page Cache enabled, to ensure the correct
 * cacheability metadata is present (and hence the expected behavior). However,
 * in the early stages of development, you may want to disable it.
 */
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null'; // uncommented to disable cache

/**
 * Allow test modules and themes to be installed.
 *
 * Drupal ignores test modules and themes by default for performance reasons.
 * During development it can be useful to install test extensions for debugging
 * purposes.
 */
$settings['extension_discovery_scan_tests'] = TRUE;

/**
 * Enable access to rebuild.php.
 *
 * This setting can be enabled to allow Drupal's php and database cached
 * storage to be cleared via the rebuild.php page. Access to this page can also
 * be gained by generating a query string from rebuild_token_calculator.sh and
 * using these parameters in a request to rebuild.php.
 */
$settings['rebuild_access'] = TRUE;

/**
 * Twig debugging:
 *
 * When debugging is enabled:
 * - The markup of each Twig template is surrounded by HTML comments that
 *   contain theming information, such as template file name suggestions.
 * - Note that this debugging markup will cause automated tests that directly
 *   check rendered HTML to fail. When running automated tests, 'twig_debug'
 *   should be set to FALSE.
 * - The dump() function can be used in Twig templates to output information
 *   about template variables.
 * - Twig templates are automatically recompiled whenever the source code
 *   changes (see twig_auto_reload below).
 *
 * Note: changes to this setting will only take effect once the cache is
 * cleared.
 *
 * For more information about debugging Twig templates, see
 * <a href="http://drupal.org/node/1906392">http://drupal.org/node/1906392</a>.
 *
 * Not recommended in production environments (Default: FALSE).
 */
$settings['twig_debug'] = TRUE;

/**
 * Twig auto-reload:
 *
 * Automatically recompile Twig templates whenever the source code changes. If
 * you don't provide a value for twig_auto_reload, it will be determined based
 * on the value of twig_debug.
 *
 * Note: changes to this setting will only take effect once the cache is
 * cleared.
 *
 * Not recommended in production environments (Default: NULL).
 */
$settings['twig_auto_reload'] = TRUE;

/**
 * Twig cache:
 *
 * By default, Twig templates will be compiled and stored in the filesystem to
 * increase performance. Disabling the Twig cache will recompile the templates
 * from source each time they are used. In most cases the twig_auto_reload
 * setting above should be enabled rather than disabling the Twig cache.
 *
 * Note: changes to this setting will only take effect once the cache is
 * cleared.
 *
 * Not recommended in production environments (Default: TRUE).
 */
$settings['twig_cache'] = FALSE;

/**
 * Trusted host configuration.
 *
 * Drupal core can use the Symfony trusted host mechanism to prevent HTTP Host
 * header spoofing.
 *
 * To enable the trusted host mechanism, you enable your allowable hosts
 * in $settings['trusted_host_patterns']. This should be an array of regular
 * expression patterns, without delimiters, representing the hosts you would
 * like to allow.
 */
$settings['trusted_host_patterns'] = array(
	'^localhost$',
	'^spc.local$',
	'^spcresults.org$',
	'^www.spcresults.org$',
	'^18.219.122.232'
);

$config['system.logging']['error_level'] = 'verbose';
