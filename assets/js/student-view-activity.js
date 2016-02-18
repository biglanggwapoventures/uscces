(function($){
	$(document).ready(function(){
		$('#leave').click(function(){
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
			var that = $(this),
				id = that.data('pk'),
				modal = that.closest('.modal'),
				msgBox = modal.find('.alert-danger'),
				mobile = modal.find('input[name=mobile]').val()

			that.addClass('disabled');
			msgBox.addClass('hidden');

			$.post(that.data('action-url'), {id:id,mobile:mobile})
			.done(function(response){
				if(response.result){
					window.location.reload();
					return;
				}
				msgBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				that.removeClass('disabled');
			})
			.fail(function(){
				alert('An internal server error has occured. Please try again later.');
			})
		});
	});
})(jQuery)