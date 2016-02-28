(function($){
	$(document).ready(function(){

		$('.datepicker').datepicker();

		$('button[type=reset]').click(function(e){
			e.preventDefault();
			$(this).closest('form').find('input,select').val('');
		})

	})
})(jQuery)