(function($){
	$(document).ready(function(){	

		$(".textarea").wysihtml5({toolbar:{image:false}});
		$('.datetimepicker').datetimepicker({format:'MM/DD/YYYY hh:mm A'});

		$('form').submit(function(e){

			e.preventDefault();

			var that = $(this),
				submitBtn = that.find('[type=submit]'),
				msgBox = $('.alert-danger');

			submitBtn.attr('disabled', 'disabled');
			msgBox.addClass('hidden');

			$.post(that.data('action'), that.serialize())
			.done(function(response){
				if(response.result){
					window.location.href = $('.cancel').attr('href');
					return;
				}
				msgBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				$('html, body').animate({scrollTop: 0}, 'slow');
			})
			.fail(function(){
				alert('An internal server error has occured');
			}).always(function(){
				submitBtn.removeAttr('disabled');
			});

		});

		$('select[name=status]').change(function(){
			if($(this).val() === 'd'){
				$('textarea[name=decline_reason]').removeAttr('disabled').closest('.form-group').removeClass('hidden');
				$('input[name=facilitate]').attr('disabled', 'disabled').prop('checked', false);
				return;
			}
			$('textarea[name=decline_reason]').attr('disabled', 'disabled').closest('.form-group').addClass('hidden');
			$('input[name=facilitate]').removeAttr('disabled');
		});
		

	})
})(jQuery)