<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>eggsbook - Forgot Password</title>
    <meta name="description" content="" />
    <meta name="keywords"
        content="" />
    <meta name="author" content="" />
    <base href="{{asset('/eggsbook').'/'}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />


    <link rel="shortcut icon" href="">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css?v={{time()}}" rel="stylesheet" type="text/css">
  
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.css">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-174930128-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-174930128-1');
</script>
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
    <script type="text/javascript">
		_atrk_opts = { atrk_acct: "vaigt1zDGU20kU", domain: "dafco.org", dynamic: true };
		(function () { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(as, s); })();
	</script>
	<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none"
			height="1" width="1" alt="" /></noscript>

</head>
	<body>
	<canvas id="nokey" width="800" height="800"></canvas>

		<!-- Begin page -->
		<div class="bgform_auth"></div>
        <div class="wrapper-page wrapper-page-form">
            <div class="panel panel-color panel-primary panel-pages bg-form">
				<div class="borderAnimation">
				<span></span>
				<span></span>
				<span></span>
				<span></span>

			</div>
                <div class="panel-body">
                    <h3 class="text-center m-t-0 m-b-30">
                        <span class=""><img src="luckybox/logo.png" alt="logo" height="200"></span>
                    </h3>
                    <h4 class="text-white text-center m-t-0"><b>Reset Password</b></h4>

                    <form method="POST" class="form-horizontal m-t-20" action="{{ route('postForgotPassword') }}">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="Email" type="email" required="" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-lucky w-md waves-effect waves-light" type="submit">Send Mail</button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('getLogin') }}" class="text-white">Back to LOGIN !</a>
                            </div>
                        </div>

                    </form> 
                </div>

            </div>
        </div>



        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/canvas.js?v={{time()}}"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/js/app.js"></script>

        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<script type="text/javascript">
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
