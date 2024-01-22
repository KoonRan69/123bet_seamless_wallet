@extends('System.Layouts.Master')
@section('title', 'Dashboard')
@section('css')

<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--Morris Chart CSS -->
<link rel="stylesheet" href="assets/plugins/morris/morris.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css">
<style>
	.loader-time {
		border: 5px solid #f3f3f3;
		border-top: 5px solid #f59f2e;
		border-radius: 50%;
		width: 40px;
		height: 40px;
		display: inline-block;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}

	.fz-20 {
		font-size: 20px;
	}

	.owl-theme .owl-controls {
		margin-top: 0px;
		text-align: center;
		position: absolute;
		top: -30px;
		right: 10px;
	}

	.owl-theme .owl-controls .owl-buttons div {
		color: #FFF;
		display: inline-block;
		zoom: 1;
		*display: inline;
		margin: 0 5PX;
		padding: 0 5PX;
		font-size: 20PX;
		-webkit-border-radius: 30px;
		-moz-border-radius: 30px;
		/* border-radius: 30px; */
		/* background: #869791; */
		filter: Alpha(Opacity=50);
	}

	.owl-theme .owl-controls .owl-buttons div.owl-prev:before {
		position: absolute;
		content: '\f177';
		left: 0;
		font-size: 18px;
		top: 0;
		font: normal normal normal 14px/1 FontAwesome;
	}

	.owl-theme .owl-controls .owl-buttons div.owl-next:before {
		position: absolute;
		content: '\f178';
		right: 0;
		top: 0;
		font-size: 18px;
		font: normal normal normal 14px/1 FontAwesome;
	}

	.panel-body .table-responsive tbody tr th,
	.panel-body .table-responsive tbody tr td {
		color: #fff;
		font-weight: 600;
		font-size: 16px;
		font-family: 'Prompt', sans-serif;
	}

	@media screen and (max-width: 475px) {

		.panel-body .table-responsive tbody tr th,
		.panel-body .table-responsive tbody tr td {
			font-size: 13px;
		}
	}

	.panel-body .table-responsive tbody tr td {
		text-align: right;
	}

	.text-red {
		color: #f00 !important;
	}

	.balance-row h3 {
		color: #ffda8b;
		margin: 0;
		text-transform: uppercase;
		font-size: 20px;
	}

	.balance-row td {
		vertical-align: middle !important;
	}

	.fz-18 {
		font-size: 1.2em;
		margin-bottom: 0;
		padding-bottom: 5px;
	}

	.icon-img img {
		width: 100%;
	}

	.icon-img {
		float: left;
		width: 60px;
		/* border-radius: 50%; */
		margin-right: 10px;
		display: block;
		background: #ffffff;
		padding: 5px;
		border: 1px #058988 solid;
	}

	@media (min-width: 768px) {
		.offset-md-2 {
			margin-left: 16.6666667%;
		}
	}

	@media (max-width: 767px) {
		.table-responsive.no-rps {
			overflow-x: hidden;
		}

		.panel-body .table-responsive.no-rps tbody tr th,
		.panel-body .table-responsive.no-rps tbody tr td {
			width: 100%;
			display: block;
			text-align: center;
			border-top: 0;
		}

		.panel-body .table-responsive.no-rps tbody tr td {
			text-align: center;
			width: 100%;
			display: block;
		}

		.panel-body .table-responsive.no-rps tbody tr {
			border: 1px #058988 solid;
			margin: 10px 0;
			display: block;
		}

		.balance-row h3 {
			font-size: 14px;
		}

		.btn {

			padding: 5px 9px;
		}

		.icon-img {
			width: 50px;
		}
	}

	.mt-2 {
		margin-top: 1em;
		margin-bottom: 1em;
	}
 
    .nav.nav-tabs>li>a,
	.nav.tgame-bet >li>a {
		background-color: transparent;
		border-radius: 0;
		border: none;
		cursor: pointer;
		line-height: 50px;
		font-weight: 500;
		padding-left: 20px;
		padding-right: 20px;
		font-family: 'Prompt', sans-serif;
	background:	linear-gradient(to top, #db9d2b, #e9c56c)!important;
		color: white !important;
		text-transform: uppercase;
	}

	.nav.nav-tabs>li.active>a {
		background: linear-gradient(-90.4deg, #D69625  0%,#ffcb54 25%,#D69625 50%,#ffcb54 75%, #D69625 100%)!important;
		border: 0;
		color: white !important;
	}

	.nav.nav-tabs>li>a:hover,
	.nav.tabs-vertical>li>a:hover {
		color: #fff !important;
		background: linear-gradient(-90.4deg, #D69625  0%,#ffcb54 25%,#D69625 50%,#ffcb54 75%, #D69625 100%)!important;
	}

	.panel-primary > .panel-heading i.fa-usd {
    position: absolute;
    border: 2px #f3f3f3 solid;
    top: 0px;
    padding: inherit;
    width: max-content;
    background: linear-gradient(to bottom, #ffffff, #efeeef);
    box-shadow: 4px 4px 4px 1px rgba(0, 0, 0, 0.2);
    font-size: 41px;
    color: black;
    left: 10px;
  

}
</style>
@endsection
@section('content')
<div class="content">
	<div class="container">

		<!-- Page-Title -->
		<div class="row">
			<div class="col-sm-12">
				<div class="page-header-title">
					<h4 class="pull-left page-title">Dashboard</h4>
					<ol class="breadcrumb pull-right">
						<li><a href="javascript:void(0);">DAPP</a></li>
						<li class="active">Dashboard</li>
					</ol>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-lg-6 col-xl-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <img src="assets/images/coin/USDT.png">
                       
                        <h3 class="panel-title text-right"> USDT</h3>
                    </div>
                    <div class="panel-body text-center">
						<p class="line-bottom text-white fz-18" style="color:#ffda8b!important;padding: 25px 0;font-size: 1.5em;"><b><span class="loader balanceUSD"
							id="balanceUSD"></span> USDT </b></p>
							<a href="{{ route('system.getWithdraw') }}" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> Withdraw</a>
							<a href="{{ route('system.getDeposit') }}" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> Deposit</a>
                    </div>
                </div>
            </div>
		
			<div class="col-md-12 col-lg-6 col-xl-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
						<i class="fa fa-usd" aria-hidden="true"></i>
						<h3 class="panel-title text-right">Balance Income</h3>
                    </div>
                    <div class="panel-body text-center">
						<p class="line-bottom text-white fz-18" style="color:#ffda8b!important;padding: 25px 0;font-size: 1.5em;"><b><span class="loader balanceIncome"
							id="balanceIncome"></span> USDT </b></p>
							<button data-toggle="modal"
							data-target="#modal_income" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> Withdraw</button>
							
                    </div>
                </div>
			</div>
		</div>
		<div class="row">
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <img src="luckybox/bmg.png">

                        <h3 class="panel-title text-right"> BMG</h3>
                    </div>
                    <div class="panel-body text-center">
						<p class="line-bottom text-white fz-18" style="color:#ffda8b!important;padding: 25px 0;font-size: 1.5em;"><b><span class="loader" id="balanceBMG"></span>
							BMG</b></p>
							<a href="{{ route('system.getWithdraw') }}" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> Withdraw</a>
						<a href="{{ route('system.getDeposit') }}" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> Deposit</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <img src="luckybox/logo.png" width="85">

                        <h3 class="panel-title text-right">Points</h3>
                    </div>

                    <div class="panel-body text-center">
						<p class="line-bottom text-white fz-18" style="color:#ffda8b!important; padding: 25px 0;font-size: 1.5em;"><b><span class="loader" id="balancePoint"></span>
							POINTS</b></p>
						
						<div class="hiden" style="opacity: 0!important">
							
							<a href="" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> 0</a>
						<a href="" class="btn btn-lucky waves-effect waves-light"><i
								class="fa fa-usd"></i> 0</a>
						</div>
                    </div>
                </div>
            </div>
        </div>
        
		<div class="row mt-3">
			<div class="col-lg-4 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading text-uppercase">Maxout Income
						<div class="pull-right">
							@if($total_invest > 0)
							@if($chartMaxout[0]['value'] >= ($total_invest*2))
							<div class="text-danger">  
								<div id="calltrap-btn" style="cursor: pointer;" class="row b-calltrap-btn calltrap_offline hidden-phone visible-tablet">
									<div id="calltrap-ico"><p><i class="fa fa-bell" aria-hidden="true"></i></p></div>
								</div>
							</div>
							@endif
							@endif
						</div>
					</div>
					<div class="panel-body">
						<canvas id="chart-area"></canvas>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading text-uppercase">
						<i class="fa fa-table"></i> Investment
						<div class="pull-right">
							<div class="text-white">TOTAL: {{number_format($total_invest, 2)}} USDT</div>
						</div>
					</div>
					<div class="panel-body p-t-10">
						<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Amount</th>
										<th>Time</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($history_invest as $item)
									
									<tr>
										<td class="text-success">{{ number_format($item->investment_Amount, 2) }} USDT</td>
										<td>{{ Date('Y-m-d H:i:s', $item->investment_Time)}}</td>
										<td>
											@if ($item->investment_Status == 1)
											<label class="label label-success">Success</label>
											@elseif($item->investment_Status == 2)
											<label class="label label-info">Expired</label>
											@else
											<label class="label label-warning">Cancel</label>
											@endif
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
							{{$history_invest->links()}}
						</div>
					</div>
				</div>
			</div>
		</div>


	</div> <!-- container -->

</div> <!-- content -->
<div id="modal_income" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
		<form action="{{route('system.postWithdrawIncome')}}" method="post">
                @csrf
                <div class="modal-header">
					<h5 style="margin: 10px 0px 0px 10px;flex: 1;" class="modal-title" id="myModalLabel" >
                        Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="flex: 0;">×</button>
                   
                </div>
                <div class="modal-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body pa-0">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="form-wrap">
                                                <div class="form-body overflow-hide">
                                                    <div class="form-group">
                                                        <label class="mb-10 text-dark" for="exampleInputpwd_1">Amount</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="icon-lock"></i>
                                                            </div>
                                                            <input type="number" class="form-control"
                                                                name="amount" min="0"
                                                                placeholder="Enter Your Amount Need WithDraw">
                                                        </div>
                                                    </div>
													@include('System.Layouts.Captcha')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect btnSubmit"><i class="fa fa-floppy-o"
                            aria-hidden="true"></i>
                        Withdraw</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i
                            class="fa fa-times" aria-hidden="true"></i>
                        Cancel</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
@section('script')
<!-- Chart JS -->

<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<script src="https://www.chartjs.org/dist/2.9.3/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>

<!--Morris Chart-->
{{-- <script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael-min.js"></script>
<script src="assets/pages/morris.init.js"></script> --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js">
</script>


<script src="assets/js/dashboard.js?v={{time()}}"></script>
<script>
	$(document).ready(function(){
		$("#testimonial-slider").owlCarousel({
			items:5,
			itemsDesktop:[1000,4],
			itemsDesktopSmall:[979,3],
			itemsTablet:[768,2],
			itemsMobie:[575,1],
			pagination:false,
			navigation:true,
			navigationText:["",""],
			autoPlay:true
		});
		
	});
	
    function popitup(url,windowName) {
		newwindow=window.open(url,windowName,'height=1000,width=1500');
		if (window.focus) {newwindow.focus()}
			return false;
	}
</script>

<script>
	$('#dt-dashboard').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false,
		"order": [0, 'desc']
    });
</script>
<script>
	$(document).ready(function () {
	$('.btn-refund').click(function(){
		let invest_id = $(this).data('invest-id');
		console.log(invest_id);
		swal.fire({
			title: 'Confirm Refund Investment',
			text: 'Are You Sure Refund Investment',
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Submit',
			confirmButtonClass: 'btn btn-confirm',
			cancelButtonClass: 'btn btn-cancel',
			closeOnConfirm: true
		}).then(function (confirm) {
			console.log(confirm);
			if(confirm.value == true){
				$('.refund-'+invest_id).submit();

			}
		});
	})

	
	$('.btn-reinvest').click(function(){
		let invest_id = $(this).data('invest-id');
		console.log(invest_id);
		swal.fire({
			title: 'Confirm Reinvestment',
			text: 'Are You Sure Reinvestment',
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Submit',
			confirmButtonClass: 'btn btn-confirm',
			cancelButtonClass: 'btn btn-cancel',
			closeOnConfirm: true
		}).then(function (confirm) {
			console.log(confirm);
			if(confirm.value == true){
				$('.reinvest-'+invest_id).submit();

			}
		});
	})
});

</script>
<script>
	$(document).ready(function () {
		var config2 = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					{{$chartMaxout[0]['value']}},
					{{$chartMaxout[1]['value']}},
				],
				backgroundColor: [
					window.chartColors.yellow,
					window.chartColors.red,
					window.chartColors.white,
					window.chartColors.orange,
				],
				label: 'Dataset 1'
			}],
			labels: [
				'Commission',
				'Max Out'
			]
		},
		options: {
			responsive: true
		}
		
		
	};

	var ctx2 = document.getElementById('chart-area').getContext('2d');
	window.myPie = new Chart(ctx2, config2);
	Chart.defaults.global.defaultFontColor = "#fff";
	});
	
</script>
{{-- <script>
  var a=  $(".serviceBoxEcosystem ").width();
	console.log(a);
	console.log(111);
  $(".serviceBoxEcosystem").height(this.a);
	$(".serviceBoxEcosystem .description img").height(this.a-34);
</script>
<script>
	$(document).ready(function(){
		
					var divWidth = $("#box").innerWidth();
					var divHeight = $("#box").innerHeight();
					$("#result").html("Inner Width: " + divWidth + ", " + "Inner Height: " + divHeight);
			
	});
	</script> --}}
@endsection