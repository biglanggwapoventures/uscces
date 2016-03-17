(function($){
	$(document).ready(function(){
		$('#leave').click(function(){

			if(!confirm('Are you sure you want to stop facilitating this activity?')){
				return;
			}

			var that = $(this),
				id = that.data('pk');

			that.addClass('disabled');

			$.post(that.data('action-url'), {id:id})
			.done(function(response){
				if(response.result){
					window.location.reload();
					return;
				}
				that.after($('<p />', {'class':'text-center text-danger', text : response.messages[0]}).fadeOut('slow'));
				that.removeClass('disabled');
			})
			.fail(function(){
				alert('An internal server error has occured. Please try again later.');
			})
		});

		$('#join').click(function(){

			if(!confirm('Are you sure you want to facilitate this activity?')){
				return;
			}

			var that = $(this),
				id = that.data('pk');

			that.addClass('disabled');

			$.post(that.data('action-url'), {id:id})
			.done(function(response){
				if(response.result){
					window.location.reload();
					return;
				}
				that.after($('<p />', {'class':'text-center text-danger', text : response.messages[0]}).fadeOut('slow'));
				that.removeClass('disabled');
			})
			.fail(function(){
				alert('An internal server error has occured. Please try again later.');
			})
		});
	});
})(jQuery)