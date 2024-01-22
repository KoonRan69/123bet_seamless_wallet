<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description"  content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
    <meta name="keywords" content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
    <meta name="author"  content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
    <meta name="" content="" />
    <base href="{{asset('public/eggsbook').'/'}}">
    <title>123Betnow - @yield('title')</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css?v={{time()}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Toast CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-HF3RHQ8LQ4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());

      gtag('config', 'G-HF3RHQ8LQ4');
    </script>
    <!-- Start Alexa Certify Javascript -->
    <script type="text/javascript">
      _atrk_opts = {
        atrk_acct: "vaigt1zDGU20kU",
        domain: "123betnow.net",
        dynamic: true
      };
      (function() {
        var as = document.createElement('script');
        as.type = 'text/javascript';
        as.async = true;
        as.src = "https://certify-js.alexametrics.com/atrk.js";
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(as, s);
      })();
    </script>
    <noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none" height="1" width="1" alt="" /></noscript>
    <!-- End Alexa Certify Javascript -->
    <style>
      .swal2-header {
        font-size: 1.5rem;
      }

      .swal2-styled.swal2-confirm {
        background-color: #673AB7;
      }

      .swal2-popup {
        width: 46em;
      }

      #wrapper.enlarged .left.side-menu {
        overflow: initial !important;
      }
    </style>
    <style>
      .topfix{
        right: -35px;
        position: fixed;
        z-index: 999;
        top: 8rem;
      }
      .topfix li{
        list-style: none;
      }

      main {
        display: flex;
        justify-content: center;
        align-items: center;
        height: auto;
        padding-top: 15px;
        position: relative;
        margin-bottom: -15px;
      }
      .nav>li {
        display: inline-block;
      }
      main .notification {
        position: relative;
        /* width: 10em;
        height: 10em; */

      }

      main .notification svg {
        height: 2.5em;

      }

      main .notification svg>path {
        fill: #fff;
      }

      main .notification--bell {
        animation: bell 2.2s linear infinite;
        transform-origin: 50% 0%;
      }

      main .notification--bellClapper {
        animation: bellClapper 2.2s 0.1s linear infinite;
      }

      main .notification--num {
        position: absolute;
        top: 0%;
        left: 60%;
        font-size: 17px;
        border-radius: 50%;
        width: 1.25em;
        height: 1.25em;
        background-color: #F44336;
        border: 6px solid #F44336;
        color: #FFFFFF;
        text-align: center;
        line-height: 10px;
        animation: notification 3.2s ease;
      }

      @keyframes bell {

        0%,
        25%,
        75%,
        100% {
          transform: rotate(0deg);
        }

        40% {
          transform: rotate(10deg);
        }

        45% {
          transform: rotate(-10deg);
        }

        55% {
          transform: rotate(8deg);
        }

        60% {
          transform: rotate(-8deg);
        }
      }

      @keyframes bellClapper {

        0%,
        25%,
        75%,
        100% {
          transform: translateX(0);
        }

        40% {
          transform: translateX(-.15em);
        }

        45% {
          transform: translateX(.15em);
        }

        55% {
          transform: translateX(-.1em);
        }

        60% {
          transform: translateX(.1em);
        }
      }

      @keyframes notification {

        0%,
        25%,
        75%,
        100% {
          opacity: 1;
        }

        30%,
        70% {
          opacity: 0;
        }
      }
      .dropdown-divider{
        height: 0;
        margin: .5rem 0;
        overflow: hidden;
        border-top: 1px solid #e9ecef;
      }
      ::-webkit-scrollbar-track
      {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
      }

      ::-webkit-scrollbar
      {
        width: 5px;
        height: 7px;
        background-color: #F5F5F5;
      }

      ::-webkit-scrollbar-thumb
      {
        background-color: #daf1fd;
        background-image: -webkit-linear-gradient(45deg,
          rgba(255, 255, 255, .2) 25%,
          transparent 25%,
          transparent 50%,
          rgba(255, 255, 255, .2) 50%,
          rgba(255, 255, 255, .2) 75%,
          transparent 75%,
          transparent)
      }
      .side-menu i.fa{
        font-size: 23px;
        opacity: 0.8;
        margin-right:10px;
      }
      .form-control {
        color: #777;
      }

      body {
        color: #444!important;
      }
    </style>

    @yield('css')

  </head>


  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

  <!-- DataTables -->
  <link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <style>
    a:hover {
      cursor: pointer;
    }

    .btn-filler {
      margin-bottom: 10px;
    }

    .pagination {
      float: right;
    }
  </style>

  <!--THIS PAGE LEVEL CSS-->
  <link
        href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
        rel="stylesheet" />
  <link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
        rel="stylesheet" />
  <link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css"
        rel="stylesheet" />
  <link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css"
        rel="stylesheet" />
  <link href="datetime/plugins/bootstrap-daterange/daterangepicker.css"
        rel="stylesheet" />
  <link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
  <link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
  <!--REQUIRED THEME CSS -->
  <link href="datetime/assets/css/style.css" rel="stylesheet">
  <link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
  <style>
    .dtp-btn-cancel {
      background: #9E9E9E;
    }

    .dtp-btn-ok {
      background: #009688;
    }

    .dtp-btn-clear {
      color: black;
    }

    .edit-email-input {
      padding-left: 10px;
      border: 2px dotted #f5b61a;
      width: 75%;
      background: #ffffff47;
    }
  </style>
  
  <body class="fixed-left" style="background-image: url('img/background-admin.jpeg')!important">
    <div id="wrapper">
      <!-- Top Menu Items -->
    	@include('Provide.Layouts.Header')
      <!-- /Top Menu Items -->
		@include('Provide.Layouts.Menu')
      <!-- Left Sidebar Menu -->
      
      <!-- /Left Sidebar Menu -->

      <!-- Main Content -->
      <div class="content-page">
        <div class="backgroungImg"></div>
        @yield('content')

        <!-- Footer -->
      
        <!-- /Footer -->

      </div>
      <!-- /Main Content -->

    </div>
    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
      var url_webhost = window.location.protocol+'//' + window.location.host;

    </script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
   

    @yield('script')
  </body>

</html>
