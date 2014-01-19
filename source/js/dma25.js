var dma = {};

(function($){
	
	$(document).ready(function(){
			$('.f90-delete-my-account').click(function(){
					if(confirm("Are you sure you want to delete your account? \n After deleting account you won't be able to recover it.")){
						dma.delete_request();
					}
					return false;
			});
	});
	
	dma.delete_request = function(){
		$.ajax({
			url: "index.php?option=com_f90dma&task=sendDeleteRequest"
			}).done(function(data) {
				data = $.parseJSON(data);
				
				$('body').append(data.html);
				
				if(data.error == false){
					alert('Your account has been deleted successfully.');					
					$("#f90dma-login-form").submit();
				}
				else{
					alert(data.html);
				}
			}).fail(function() {
				alert('There is some error in sending request to admin. Please contact them by using any other means.');
			});
	};
	
})(jQuery);
