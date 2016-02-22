(function($){
	$(document).ready(function(){
		var resetPWUrl = $('[data-name=reset-pw-url]').data('value');

		$('#confirm').on('show.bs.modal', function(e){

			var modal = $(this),
				id = $(e.relatedTarget).closest('tr').data('pk'),
				confirmBtn = modal.find('#confirm-button'),
				messageBox = modal.find('p');

			messageBox.text(function(){
				return $(this).data('text-init');
			});

			confirmBtn.removeClass('hidden');

			$('#confirm-button').click(function(){
				var btn = $(this);

				btn.addClass('disabled');

				$.post(resetPWUrl, {'id':id})
				.done(function(response){
					if(response.result){
						messageBox.text('Password has been successfully reset!');
						btn.addClass('hidden');
						return;
					}
				})
				.fail(function(){
					alert('An internal server error has occured. Please try again later.');
				})
				.always(function(){
					btn.removeClass('disabled');
				});
			})
			
		});

		$('#confirm').on('hide.bs.modal', function(e){
			$('#confirm-btn').unbind();
		});

		$('[type=reset]').click(function(e){
			e.preventDefault();

			$(this).closest('form').find('input,select').val('');
		});
	});
})(jQuery)