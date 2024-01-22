<script src="assets/js/jquery.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src='https://www.google.com/recaptcha/api.js?hl=us'></script>
<div style="display:flex;justify-content: center;">
	<div class="g-recaptcha" data-sitekey="6Ld_4bQZAAAAAPGd0JXCWBKDZXPQWyM2lJKfSXho" data-callback="enableBtn"
		style="transform:scale(1);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>

</div>
<span id="captcha"></span>


<script>
			$('form').submit(function(e) {
						
						rcres = grecaptcha.getResponse();
						Console.log(rcres);
            if (!rcres.length) {
							
								toastr["error"]("Please Fill The Captcha!")
						
                e.preventDefault();
            }
						grecaptcha.reset();
				
        });	
				// toastr.options = {
				// 					"maxOpened":1,
				// 					"closeButton": false,
				// 					"autoDismiss":true,
				// 					"debug": false,
				// 					"newestOnTop": false,
				// 					"progressBar": true,
				// 					"positionClass": "toast-top-center",
				// 					"preventDuplicates": true,
				// 					"onclick": null,
				// 					"showDuration": "300",
				// 					"hideDuration": "1000",
				// 					"timeOut": "1000",
				// 					"extendedTimeOut": "1000",
				// 					"showEasing": "swing",
				// 					"hideEasing": "linear",
				// 					"showMethod": "fadeIn",
				// 					"hideMethod": "fadeOut"
				// }
</script>
<script>
	// $(function(){
	// 			function rescaleCaptcha(){
	// 				var width = $('.g-recaptcha').parent().width();
	// 				var scale;
	// 				if (width < 302) {
	// 					scale = width / 302;
	// 				} else{
	// 					scale = 1.0; 
	// 				}

	// 				$('.g-recaptcha').css('transform', 'scale(' + scale + ')');
	// 				$('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
	// 				$('.g-recaptcha').css('transform-origin', '0 0');
	// 				$('.g-recaptcha').css('-webkit-transform-origin', '0 0');
	// 			}

	// 			rescaleCaptcha();
	// 			$( window ).resize(function() { rescaleCaptcha(); });

	// 		});
</script>