(function($){
	$(document).ready(function(){
		$('a.process').click(function(){

			var that = $(this),
				modal = $('#loading'),
				messageBox = modal.find('.modal-body p'),
				dismissBox = modal.find('.modal-footer'),
				url = that.data('url'),
				activityId = that.data('activity-id');

			messageBox.text('Processing request... Please wait...');
			modal.modal('show');

			modal.on('hide.bs.modal', function(){
				messageBox.text('');
			});
			
			$.post(url, {'id': activityId})
			.done(function(response){
				messageBox.text(response.message);
				if(response.result){
					modal.on('hide.bs.modal', function(){
						window.location.reload();
					});
				}
			})
			.fail(function(){
				messageBox.text('An internal server error has occured. Please try again later.');
			})
			.always(function(){
				dismissBox.removeClass('hidden');
			});

		});
	});
})(jQuery)