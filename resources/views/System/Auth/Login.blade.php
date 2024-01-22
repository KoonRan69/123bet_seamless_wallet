<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>123betnow - Login</title>
    <meta name="description" content="" />
    <meta name="keywords"
        content="" />
    <meta name="author" content="" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />
    <base href="{{asset('public/')}}/">
    <link href="eggsbook/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="eggsbook/assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="eggsbook/assets/css/style.css?v={{time()}}" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.css">

    <style>
    .swal2-header{
        font-size: 1.5rem;
    }
    .swal2-styled.swal2-confirm{
        background-color: #673AB7;
    }
    .swal2-popup{
        width: 46em;
    }
	@media (max-width: 767px) {
		.bg-page li:nth-child(5) {
			top: 20%;

		}

		.bg-page li:nth-child(5) {
			top: 20%;
		}
	}
	.bg-page {
		list-style-type: none;
		position: fixed;
		width: 100%;
		height: 100%;
		margin: 0;
		padding: 0;
		top:0;
		left:0;
		right:0;
		    z-index: 1;
	}
	.bg-page li:nth-child(1) {
		left: 20%;
		top: 15%;
	}

	.bg-page li:nth-child(2) {
		left: 80%;
		top: 15%;
	}

	.bg-page li:nth-child(3) {
		left: 50%;
		top: 15%;
	}

	.bg-page li:nth-child(4) {
		left: 10%;
		top: 50%;
	}

	.bg-page li:nth-child(5) {
		top: 35%;
		left: 60%;

	}
	.pulse {
		/* margin:100px; */
		display: block;
		width: 100px;
		height: 100px;
		border-radius: 50%;
		background: -webkit-gradient(linear, left top, right top, from(#00798099), to(#00a68e80));
		background: -o-linear-gradient(left, #00798099, #00a68e80);
		background: linear-gradient(to right, #00798099, #00a68e80);
		cursor: pointer;


		position: relative;
		top: 10%;
	}
	@media (max-width: 499px) {
		.pulse {
			width: 75px !important;
			height: 75px !important;
		}
	}
	.pulse::after {
		content: "";
		width: 100%;
		height: 100%;

		-webkit-animation: pulse 2s infinite;

		animation: pulse 2s infinite;
		border-radius: 50px;
		position: absolute;
	}

	.pulse:hover {
		-webkit-animation: none;
		animation: none;
	}

	@-webkit-keyframes pulse {
		0% {
			-webkit-box-shadow: 0 0 0 0 #04f1ce80;
			box-shadow: 0 0 0 0 #04f1ce80;

			opacity: 0.3;
		}


		100% {
			-webkit-box-shadow: 0 0 0 50px #04f1ce3d;
			box-shadow: 0 0 0 50px #04f1ce3d;
			opacity: 0.1;
		}
	}

	@keyframes pulse {
		0% {
			-webkit-box-shadow: 0 0 0 0 #04f1ce80;
			box-shadow: 0 0 0 0 #04f1ce80;

			opacity: 0.3;
		}


		100% {
			-webkit-box-shadow: 0 0 0 50px #04f1ce3d;
			box-shadow: 0 0 0 50px #04f1ce3d;
			opacity: 0.1;
		}
	}
			.panel.bg-form{
				z-index:2;
			}
	.borderAnimation{
	  position: absolute;
	  top: 0%;
	  left: 0%;
	  right:0;
	  width:100%;
	  height:100%;
	  font-size: 30px;
	  letter-spacing: 2px;
	  text-decoration: none;
	  text-transform: uppercase;
	  box-shadow: 0 20px 50px rgba(0,0,0,0.5);
	  overflow: hidden;
	}

	.borderAnimation span:nth-child(1){
	  position: absolute;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 2px;
	  background: linear-gradient(to right, #ff5a00 , #ffac00);
	  animation: animate1 2s linear infinite;
	  animation-delay: 1s;
	}
	@keyframes animate1{
	  0%{
		transform: translateX(-100%);
	  }
	  100%{
		transform: translateX(100%);
	  }
	}

	.borderAnimation span:nth-child(2){
	  position: absolute;
	  top: 0;
	  right: 0;
	  width: 2px;
	  height: 100%;
	  background: linear-gradient(to right, #ff5a00 , #ffac00);
	  animation: animate2 2s linear infinite;
	}

	@keyframes animate2{
	  0%{
		transform: translateY(-100%);
	  }
	  100%{
		transform: translateY(100%);
	  }
	}
	.panel{
		border:0;
		z-index: 3;
	}
	.borderAnimation span:nth-child(3){
	  position: absolute;
	  bottom: 0;
	  left: 0;
	  width: 100%;
	  height: 2px;
	  background: linear-gradient(to right, #ff5a00 , #ffac00);
	  animation: animate3 2s linear infinite;
	  animation-delay: 1s;
	}
	@keyframes animate3{
	  0%{
		transform: translateX(100%);
	  }
	  100%{
		transform: translateX(-100%);
	  }
	}

	.borderAnimation span:nth-child(4){
	  position: absolute;
	  top: 0;
	  left: 0;
	  width: 2px;
	  height: 100%;
	  background: linear-gradient(to right, #ff5a00 , #ffac00);
	  animation: animate4 2s linear infinite;
	}
	@keyframes animate4{
	  0%{
		transform: translateY(100%);
	  }
	  100%{
		transform: translateY(-100%);
	  }
	}
	canvas{
		    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    z-index: 2;
	}
	.g-recaptcha{
		position: relative;
		z-index: 1050;
	}
    </style>

</head>

<body style="background: url('eggsbook/img/bg-sea.jpg')!important">
	<canvas id="nokey" width="800" height="800"></canvas>

	<div class="bgform_auth"></div>
    <div class="wrapper-page wrapper-page-form">
        <div class="panel panel-color panel-primary panel-pages gradient-border">
            <div class="panel-body">
                <h3 class="text-center m-t-0 m-b-30">
                   <!-- <span class=""><img src="img/logo-business-light.png" alt="logo" width="90%" style="filter: drop-shadow(rgba(0, 0, 0, 0.5) 0px 5px 2px);"></span>-->
                </h3>
                <h4 class="text-white text-center m-t-0"><b>Sign In</b></h4>

                <form class="form-horizontal m-t-20" method="POST" action="{{route('postLogin')}}" >
                    @csrf
                    <input class="form-control" name="redirect" type="hidden" value="{{request()->input('redirect')}}">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" type="email" required="" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" name="password" type="password" required=""
                                placeholder="Password">
                        </div>
                    </div>
										{{-- <div class="form-group">
											<div class="col-xs-12">
												<input type="checkbox"  >
  											<label for="vehicle1"> Remember Me</label><br>
											</div>
										</div> --}}
<!-- 										@include('System.Layouts.Captcha') -->
                    <div class="form-group text-center m-t-20 row ">


                            <button class="btn btn-success w-md waves-effect waves-light btnSubmit"  type="submit" ><i class="fa fa-sign-in" aria-hidden="true"></i> Sign In</button>


                    </div>

                    <!--<div class="form-group m-t-30 m-b-0">
                        <div class="col-sm-7">
                            <a href="{{ route('getForgotPassword') }}" class="text-white"><i
                                    class="fa fa-lock m-r-5"></i> Forgot
                                your password?</a>
                        </div>
                        <div class="col-sm-5 text-right">
                            <a href="{{ route('getRegister') }}" class="text-white url_register">Create an account</a>
                        </div>
                    </div>-->
                </form>
            </div>

        </div>
		</div>
		<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true" style="z-index:999999">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background:#fff0;padding: 0;border: 0;min-height: 0;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"
						style="width:40px;height:40px;position: absolute;background: #d69625;opacity: 1;color: #fff;margin-top: 0px;right: 0;top: 0;z-index: 1;">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:0">
					<div id="testimonial-slider" class="owl-carousel">
						{{-- @foreach($noti_image as $noti)
						<div class="testimonial">
							<img src="{{ asset('storage/'.$noti->Url.'')}}" alt="" srcset="" width="100%">
						</div>

						@endforeach --}}



					</div>
				</div>
			</div>
		</div>
	</div>
    <script src="eggsbook/assets/js/jquery.min.js"></script>
    <script src="eggsbook/assets/js/canvas.js?v={{time()}}"></script>
    <script src="eggsbook/assets/js/bootstrap.min.js"></script>
    <script src="eggsbook/assets/js/modernizr.min.js"></script>
    <script src="eggsbook/assets/js/detect.js"></script>
    <script src="eggsbook/assets/js/fastclick.js"></script>
    <script src="eggsbook/assets/js/jquery.slimscroll.js"></script>
    <script src="eggsbook/assets/js/jquery.blockUI.js"></script>
    <script src="eggsbook/assets/js/waves.js"></script>
    <script src="eggsbook/assets/js/wow.min.js"></script>
    <script src="eggsbook/assets/js/jquery.nicescroll.js"></script>
    <script src="eggsbook/assets/js/jquery.scrollTo.min.js"></script>

    <script src="eggsbook/assets/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css'>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js'></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
		<script>
		$(document).ready(function(){
		$("#testimonial-slider").owlCarousel({
			items:1,
			itemsDesktop:[1000,1],
			itemsDesktopSmall:[979,1],
			itemsTablet:[769,1],
			pagination:true,
			transitionStyle:"backSlide",
			autoplay:true
		});
	});

	</script>
    <script type="text/javascript">
    $(document).ready(function() {
	    @if(request()->input('redirect'))
	    	_urlParam = "{{decrypt(request()->input('redirect'))}}";
	    	console.log(_urlParam);
	    	_ref = GetURLReferrer(_urlParam, 'ref');
	    	$(".url_register").attr("href", "{{route('getRegister')}}?"+_ref);
// 	    	console.log(_ref);
	    @endif
		@if(Session::has('otp'))
			var CSRF_TOKEN = '{{ csrf_token() }}';
			swal.fire({
				title: 'Enter Authentication',
				text: 'Please enter authentication code.',
				input: 'text',
				type: 'input',
				name: 'txtOTP',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Submit',
				showLoaderOnConfirm: true,
				confirmButtonClass: 'btn btn-confirm',
				cancelButtonClass: 'btn btn-cancel'
				}).then(function (otp) {
					console.log(otp);
					$.ajax({
						url: "{{route('postLoginCheckOTP')}}",
						type: 'POST',
						data: {_token: CSRF_TOKEN, otp:otp.value},
						success: function (data) {
							console.log(data);
							if(data == 1){
								location.href = "{{route('system.admin.getBlogEvent')}}";
							}else{
								swal.fire({
									title: 'Error',
									text: "Authentication Code Is Wrong",
									type: 'error',
									confirmButtonClass: 'btn btn-confirm',
									allowOutsideClick: false
								}).then(function() {
									location.href = "{{route('getLogin')}}";
								})
							}
						}
					});
		    	});
		@endif
	});

    @if(Session::get('flash_level') == 'success')
        toastr.success('{{ Session::get('flash_message') }}', 'Success!', {timeOut: 3500})
    @elseif(Session::get('flash_level') == 'error')
        toastr.error('{{ Session::get('flash_message') }}', 'Error!', {timeOut: 3500})
    @endif

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            toastr.error('{{$error}}', 'Error!', {timeOut: 3500})
        @endforeach
    @endif

		</script>
</body>

</html>
