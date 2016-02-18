(function(jQuery){
	$(document).ready(function(){

		$('#activity-options').on('show.bs.modal', function(e){
			console.log(e)
			$('.options').attr('href', function(){
				return $(this).data('href')+$(e.relatedTarget).closest('tr').data('pk')
			});
		})

	})
})($)