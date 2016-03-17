(function($){

	$(document).ready(function(){

		var approveUrl = $('table').data('approve-url'),
			removeUrl = $('table').data('remove-url'),
			markClearedUrl = $('table').data('mark-cleared-url'),
			getUrl = function(action){
				if(action === 'remove'){
					return removeUrl;
				}else if(action === 'approve'){
					return approveUrl;
				}else if(action === 'mark-cleared'){
					return markClearedUrl;
				}
			},
			submit = function(url, studentId){
				$.post(url, {applicants:studentId})
				.done(function(response){
					if(response.result){
						window.location.reload();
					}else{
						alert(response.message);
					}
				})
				.fail(function(){
					alert('An internal server error has occured. Please try again later.');
				})
			};

		$('.check-all').change(function(){
			$('.check').prop('checked', $(this).prop('checked'));
		});

		$('.check').change(function(){
			if(!$(this).prop('checked')){
				$('.check-all').prop('checked', false);
			}
		});

		$('.process-one').click(function(){

			if(!confirm('Confirm action')){
				return;
			}

			var that = $(this),
				action = that.data('action'),
				url = getUrl(action),
 				tr = that.closest('tr'),
				studentId = tr.data('pk');
			submit(url, studentId);
		});

		$('.process-multiple').click(function(){

			if(!confirm('Confirm action')){
				return;
			}

			var that = $(this),
				action = that.data('action'),
				url = getUrl(action),
				studentIds = [];
			$.each($('input.check:checked'), function(i, v){
				studentIds.push($(v).val());
			});
			if(studentIds.length){
				submit(url, studentIds);
			}
			
		});

	})

})(jQuery)