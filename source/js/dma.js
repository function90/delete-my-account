var dma = {};

(function($){
	
	$(document).ready(function(){
			$('.f90-delete-my-account').live('click', function(){
														$('#f90-delete-my-account-modal').modal('show');
														$('#dma-send-request').show();
														return false;
											});
	});
	
	dma.delete_request = function(){
		$('#dma-send-request').button('loading');
		$.ajax({
			url: "index.php?option=com_f90dma&task=sendDeleteRequest"
			}).done(function(data) {
				data = $.parseJSON(data);
				
				$('#f90-delete-my-account-modal .modal-body').html(data.html);
				$('#f90-delete-my-account-modal .modal-footer').html('');
				
				if(data.error == false){					
					setTimeout(function(){$("#f90dma-login-form").submit();}, 3000);
				}
			}).fail(function() {
				$('#f90-delete-my-account-modal .modal-body').html('<p>There is some error in sending request to admin. Please contact them by using any other means.');
				$('#dma-send-request').hide();
				$('#dma-send-request').button('reset');
			});
	};
	
})(jQuery);