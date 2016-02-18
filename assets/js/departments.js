(function($){

	var tableListing = $('table#listing'),
		departments = [],
		mode,
		currentIndex,
		loadingModal =  $('#loading'),

		actionsTemplate = function(){
			return '<a class="btn btn-flat btn-info btn-xs update">Update</a> <a class="btn btn-flat btn-danger btn-xs delete">Delete</a>';
		},

		newRow = function(data, index){
			if(typeof index === 'undefined'){
				index = departments.length-1;
			}
			return '<tr data-index="'+index+'"><td>'+data.name+'</td><td>'+actionsTemplate()+'</td></tr>';
		},
		
		getDepartments = function(){
			loadingModal.modal('show').find('.modal-body p').text('Retrieving list of departments...');
			var request = $.getJSON(tableListing.data('get'));
			return request.done(function(response){
				departments = response.data;
				populateTable();
				loadingModal.modal('hide');
			});
		}, 

		populateData = function(data){
			$.each(data, function(k, v){
				$('#manage form [name='+k+']').val(v);
			});
		},

		populateTable = function(){
			var tr = [];
			$.each(departments, function(i, v){
				tr.push(newRow(v, i));
			})
			tableListing.find('tbody').html(tr.join(''));
		},

		showUpdateModal = function(){
			var that = $(this), 
				modal = $('#manage');
			currentIndex = that.closest('tr').data('index');
			populateData(departments[currentIndex]);
			modal.modal('show');
		},

		submitDepartment = function(form){
			var alert = form.find('.alert').addClass('hidden'),
				data = form.serializeObject(),
				url;

			if(mode === 'create'){
				url = form.data('create-url')
			}else{
				data.id = departments[currentIndex].id,
				url = form.data('update-url')
			}

			var request = $.post(url, data);

			request.done(function(response){
				if(response.result){
					if(mode === 'create'){
						data.id = response.data.id;
						departments.push(data);
						tableListing.find('tbody').append(newRow(data));
					}else{
						departments[currentIndex] = data;
						$('tr[data-index='+currentIndex+']').replaceWith(newRow(data, currentIndex));
					}
					form.closest('.modal').modal('hide');
				}else{
					alert.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				}
			});

		},

		showDeleteModal = function(){
			var that = $(this), 
				modal = $('#delete');
			modal.on('show.bs.modal', function(){
				currentIndex = that.closest('tr').data('index');
			})
			modal.modal('show');
		},

		deleteDepartment = function(){
			var modal = $('#delete');
			var request = $.post(modal.data('delete-url'), {id:departments[currentIndex].id});
			request.done(function(response){
				if(response.result){
					$('tr[data-index='+currentIndex+']').remove();
					modal.modal('hide');
				}
			})
		}


	$(document).ready(function(){

		getDepartments();

		$('#manage').on('show.bs.modal', function(e){
			var btn = $(e.relatedTarget), 
				that = $(this);
			mode = btn.data('modal-mode');
			if(mode === 'create'){
				that.find('h4.modal-title').text(btn.data('modal-title'));
				that.find('form input').val('');
			}else{
				mode = 'update';
				that.find('h4.modal-title').text('Update department');
			}
			that.find('.alert').addClass('hidden');
		});

		$('.modal form').submit(function(e){
			e.preventDefault();
			submitDepartment($(this));
		});

		tableListing.on('click', '.update', showUpdateModal);
		tableListing.on('click', '.delete', showDeleteModal);
		$('button#btn-delete').click(deleteDepartment)

	});
})(jQuery);