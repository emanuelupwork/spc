(function ($) {
	var GLOBAL = {};
	
	/**
	 * Expand/Collapse Content
	 * 
	 * @param e - jQuery Object
	 *
	 * @returns {Void}
	 */
	GLOBAL.toggleExpandContent = function(e){
		e.preventDefault();
		var $btn = $(this);
		var $parent = $btn.parents('.js-expand-container');
		$parent.toggleClass('collapsed');
	}
	
	GLOBAL.initPopover = function(){
		$('[data-toggle="popover"]').popover();
		$('[data-toggle="tooltip"]').tooltip();
	}
	
	$(window).load(function(){
		GLOBAL.initPopover();
		$(document).on('click', '.js-btn-expand-content', GLOBAL.toggleExpandContent);
	});
})(jQuery);