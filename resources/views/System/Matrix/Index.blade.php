@extends('System.Layouts.Master')
@section('title', 'Member Matrix')
@section('css')
<link href="assets/chart/style.css?v={{time()}}" rel="stylesheet">
<link href="assets/chart/jquery.orgchart.css?v={{time()}}" rel="stylesheet">
<style>
	a:hover {
		cursor: pointer;
	}
</style>

<style>
	#wrapper {
		overflow-x: hidden;
		overflow-y: inherit;
	}

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

	.panel-body .table-responsive tbody tr th, .panel-body .table-responsive tbody tr td {
    color: rgb(255 255 255 / 0.9);
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
		color: #5d5d5d;
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

		#selected-node {
			width: 100% !important;
		}
	}

	.modal-content {
		background-color: #fff !important;
	}

	.d-none {
		display: none;
	}

	@media (min-width: 1200px) {
		.d-lg-block {
			display: block;
		}

		.d-lg-none {
			display: none;
		}
	}

	d-block {
		display: block;
	}

	.text-color-green {
		color: #fff;
		font-weight: 600;
		font-size: 16px;
		font-family: 'Prompt', sans-serif;
	}

	.j-first-timer span {
		color: #fff;
	}

	.k-first-timer i {
		color: #fff !important;
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
					<h4 class="pull-left page-title">Member Matrix</h4>
					<ol class="breadcrumb pull-right">
						<li><a href="javascript:void(0);">DAFF</a></li>
						<li class="active">Member Matrix</li>
					</ol>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		{{-- <div class="row">
			<div class="col-md-6 col-lg-4 col-xl-4 " style="margin:auto;float:none;">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">COUNTDOWN MATRIX PACKAGE</h3>
					</div>
					<div class="panel-body">
						<div class="j-first-timer"></div>
					</div> <!-- panel-body -->
				</div> <!-- panel -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Link Ref</h3>
					</div>
					<div class="panel-body">
						<div class="form-group">
							<div class="input-group m-b-15">
								<div class="bootstrap-timepicker">
									<input id="linkRef" placeholder="ID"
										value="{{route('system.matrix.getMatrix')}}?ref={{Session('user')->User_ID}}" type="text"
										class="form-control">
								</div>
								<span class="input-group-addon btn-lucky">
									<a class="copytooltip " id="tooltiptext" onclick="copyToClipboard()"
										onmouseout="hoverCopyTooltip()" style="color:#fff"><i class="fa fa-clone"></i> Copy</a>
								</span>
							</div>
						</div>
					</div> <!-- panel-body -->
				</div> <!-- panel -->
			</div>

			<div class="col-md-6">
				<div class="panel panel-primary text-center">
					<div class="panel-heading">
						<h4 class="panel-title text-left">Balance</h4>
					</div>
					<div class="panel-body" style="padding: 10px 20px 20px;">
						<div class="table-responsive no-rps">
							<table class="table">
								<tbody class="balance-row">
									<tr>
										<th scope="row">
											<div class="icon-img"><img src="assets/images/coin/money-bag.png"></div>
											<h3> Balance Matrix</h3>
											<p class="line-bottom text-white fz-18"><b>{{number_format($user->User_BalanceMatrix,2)}} USDT
												</b></p>
										</th>
										<td>
											<button class="btn btn-success waves-effect waves-light" data-toggle="modal"
												data-target="#deposit-matrix"><i class="fa fa-usd"></i> Deposit</button>
											<button class="btn btn-danger waves-effect waves-light" data-toggle="modal"
												data-target="#withdraw-matrix"><i class="fa fa-usd"></i> Withdraw</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="row">
					@if(!$user->User_MatrixParent)
					<div class="col-lg-12 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">Join Matrix System</h3>
							</div>
							<div class="panel-body">
								<form action="{{ route('system.matrix.postJoinTree') }}" method="POST" id="join_matrix">
									@csrf
									<div class="form-group">
										<label class="control-label mb-10">Referrer ID</label>
										<div class="input-group m-b-15">
											<input id="ref_ID" type="text" name="parent" class="form-control" placeholder="Enter DAF ID"
												value="{{request()->input('ref') ?? Session('user')->User_Parent}}">
											<a onclick="document.getElementById('join_matrix').submit()"
												class="input-group-addon btn-lucky bg-custom b-0">Join Matrix</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					@endif
					<div class="col-lg-12 col-md-6">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title">User Information</h3>
							</div>
							<div class="panel-body" style="padding-top: 0">
								<div class="table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th scope="row">Balance USDT</th>
												<td>{{number_format($user->User_BalanceUSDT,2)}} USDT</td>
											</tr>
											<tr>
												<th scope="row">Total Income</th>
												<td>{{number_format($info['total_income'],2)}} USDT</td>
											</tr>
											<tr>
												<th scope="row">Total Member</th>
												<td>{{number_format($info['total_member'])}}</td>
											</tr>
											<tr>
												<th scope="row">Matrix Status</th>
												<td>{{$user->User_MatrixStatus == 0 ? "False" : "True"}}</td>
											</tr>
											<tr>
												<th scope="row">Total F1</th>
												<td>{{$info['count_children']}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8">

				<div class="panel panel-default card-view">
					<div class="panel-heading">
						<h6 class="panel-title txt-light"><i class="fa fa-history" aria-hidden="true"></i>
							Static Matrix Tree
						</h6>
					</div>
					<div class="panel-wrapper collapse in">
						<div class="panel-body">
							<div class="table-wrap">
								<table id="wallet-table" class="table table-striped table-bordered dt-responsive" cellspacing="0"
									width="100%">
									<thead>
									</thead>
									<tbody>
										<tr>
											<th class="text-color-green">
												LEVEL
											</th>
											@for($i = 1; $i <= 10; $i++) <th class="text-color-green">
												{{$i}}
												</th>
												@endfor
										</tr>
										<tr>
											<th class="text-color-green">
												MEMBER
											</th>
											@for($i = 1; $i <= 10; $i++) <th>
												<b>{{($info['floor'][$i]['count_member_floor'])}}</b>
												</th>
												@endfor
										</tr>
										<tr>
											<th class="text-color-green">
												MATRIX COMMISSION
											</th>
											@for($i = 1; $i <= 10; $i++) <th>
												<b>{{($info['floor'][$i]['count_sales_floor'])}}</b>
												</th>
												@endfor
										</tr>
										<tr>
											<th class="text-color-green">
												DIRECT (30%)
											</th>
											<th colspan="10" class="text-center">
												<b>{{$info['direct']}}</b>
											</th>
										</tr>
										<tr>
											<th class="text-color-green">
												MATCHING BONUS (10%)
											</th>
											<th colspan="10" class="text-center">
												<b>{{$info['indirect']}}</b>
											</th>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
			<div class="col-lg-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Matrix Tree</h3>
					</div>
					<div class="panel-body">
						<div id="edit-panel" class="view-state form-group">
							<input type="text" id="selected-node" style="border-top: 0;
	                        border-left: 0;
	                        border-right: 0;
	                        color:black;margin-bottom: 10px;
	                        background: transparent;width: 60%;min-width: 220px" placeholder="Please enter User ID">

							<button type="button" id="btn-report-path" class="btn btn-success btn-anim btn-md ml-10 weight-600"><i
									class="fa fa-search mr-10"></i><span class="btn-text">Search</span></button>
							<button type="button" id="btn-reset" class="btn btn-danger btn-md btn-anim ml-10 weight-600"><i
									class="fa fa-close mr-10"></i><span class="btn-text">Cancel</span></button>

						</div>
						<div id="chart-container" style="height: 383px;border-top: 1px #ddd solid"></div>
					</div>
				</div>
			</div>
			{{--<div class="col-lg-3 d-none d-lg-block ">
		        <div class="row">
			        @if(!$user->User_MatrixParent)
			        <div class="col-lg-12 col-md-6">
			            <div class="panel panel-primary">
			                <div class="panel-heading">
			                    <h3 class="panel-title">Join Matrix System</h3>
			                </div>
			                <div class="panel-body">
				                <form action="{{ route('system.matrix.postJoinTree') }}" method="POST" id="join_matrix">
			@csrf
			<div class="form-group">
				<label class="control-label mb-10">Referrer ID</label>
				<div class="input-group m-b-15">
					<input id="ref_ID" type="text" name="parent" class="form-control" placeholder="Enter DAF ID"
						value="{{request()->input('ref') ?? Session('user')->User_Parent}}">
					<a onclick="document.getElementById('join_matrix').submit()"
						class="input-group-addon btn-success bg-custom b-0">Join Matrix</a>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
@endif
<div class="col-lg-12 col-md-6">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">User Information</h3>
		</div>
		<div class="panel-body" style="padding-top: 0">
			<div class="table-responsive">
				<table class="table">
					<tbody>
						<tr>
							<th scope="row">Balance USDT</th>
							<td>{{number_format($user->User_BalanceUSDT,2)}} USDT</td>
						</tr>
						<tr>
							<th scope="row">Total Income</th>
							<td>{{number_format($info['total_income'],2)}} USDT</td>
						</tr>
						<tr>
							<th scope="row">Total Member</th>
							<td>{{number_format($info['total_member'])}}</td>
						</tr>
						<tr>
							<th scope="row">Matrix Status</th>
							<td>{{$user->User_MatrixStatus == 0 ? "False" : "True"}}</td>
						</tr>
						<tr>
							<th scope="row">Total F1</th>
							<td>{{$info['count_children']}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>
</div>--}}
</div>
</div>
</div>

<div id="deposit-matrix" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('system.matrix.postDepositMatrix') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 style="margin: 10px 0px 0px 10px;" class="modal-title" id="myModalLabel">Deposit Matrix</h5>
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
														<label class="mb-10 text-dark" for="exampleInputpwd_1">Main balance:
															{{$user->User_BalanceUSDT}} USDT</label>
														<div class="input-group">
															<div class="input-group-addon">
																<i class="icon-lock"></i>
															</div>
															<input type="text" class="form-control" name="amount" placeholder="Enter amount">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@include('System.Layouts.Captcha')
				<div class="modal-footer">
					<button type="submit" class="btn btn-success waves-effect btnSubmit"><i class="fa fa-floppy-o"
							aria-hidden="true"></i> Deposit</button>
					<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"
							aria-hidden="true"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="withdraw-matrix" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="{{ route('system.matrix.postWithdrawMatrix') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h5 style="margin: 10px 0px 0px 10px;" class="modal-title" id="myModalLabel">Withdraw Matrix</h5>
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
														<label class="mb-10 text-dark" for="exampleInputpwd_1">Balance Matrix:
															{{$user->User_BalanceMatrix}} USDT</label>
														<div class="input-group">
															<div class="input-group-addon">
																<i class="icon-lock"></i>
															</div>
															<input type="text" class="form-control" name="amount" placeholder="Enter amount">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@include('System.Layouts.Captcha')
				<div class="modal-footer">
					<button type="submit" class="btn btn-success waves-effect btnSubmit"><i class="fa fa-floppy-o"
							aria-hidden="true"></i> Withdraw</button>
					<button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times"
							aria-hidden="true"></i> Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection
@section('script')
<script src="assets/chart/jquery.orgchart.js?v={{time()}}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/timezz@5.0.0/dist/timezz.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/timezz/dist/timezz.min.js"></script>
<script type="text/javascript">
	const timer = new TimezZ(".j-first-timer", {
  date: "{{date('m/d/Y H:i:s', strtotime('+30 days', $getLastedActive))}} UTC+1",
  text: {
    days: " Days",
    hours: " Hours",
    minutes: " Minutes",
    seconds: " Seconds"
  },
  canContinue: false,
	template: '<span  class="text-color-green" style="font-weight: bold;font-size:3rem">NUMBER</span><span class="text-color-green">LETTER</span> ',
	finished() {
    console.log("finished");
  }
	
});
</script>
<script>
	/*
	$(document).ready(function () {
		var ref = window.location.search;
		if(ref != ''){
			var _ref = ref.substr(5);
			$('#ref_ID').val(_ref);
		}
		console.log(141);
		
	});
*/
</script>
<script>
	function copyToClipboard() {
        var copyText = document.getElementById("linkRef");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        var tooltip = document.getElementById("tooltiptext");
        tooltip.innerText = "Copied";
        alert(copyText.value);
    }
    function hoverCopyTooltip() {
//         var tooltip = document.getElementById("myTooltip");
//         tooltip.innerHTML = "Copy";
    }
</script>
<!-- Data table JavaScript -->
<script src="assets/js/bootstrap-treeview.min.js" type="text/javascript"></script>
<script>
	var sponsor = '{{ Session('user')->User_ID}}';
    var userID = '{{ Session('user')->User_ID}}';

</script>
<script type="text/javascript">
	$(function() {
    var datascource = {!! $list !!};
    var oc = $('#chart-container').orgchart({
        'data' : datascource,
        'nodeContent': 'title',
        'visibleLevel': 2,
        'createNode': function($node, data) {
        	$node.on('click', '.edge', function (event) {
            if ($(event.target).is('.fa-chevron-down')) {
                showDescendents(this, 1);
            //   showDescendents(this, 2);
            }
          });
        }
    });
    var showDescendents = function(node, depth) {
      if (depth === 1) {
        return false;
      }
      $(node).closest('tr').siblings(':last').children().find('.node:first').each(function(index, node) {
        var $temp = $(node).closest('tr').siblings().removeClass('hidden');
        var $children = $temp.last().children().find('.node:first');
        if ($children.length) {
          $children[0].style.offsetWidth = $children[0].offsetWidth;
        }
        $children.removeClass('slide-up');
        showDescendents(node, depth--);
      });
    };


    // var showDescendents = function(node, visibleLevel) {
    //   if (visibleLevel === 1) {
    //     return false;
    //   }
    //   $(node).closest('tr').siblings(':last').children().find('.node:first').each(function(index, node) {
    //     var $temp = $(node).closest('tr').siblings().removeClass('hidden');
    //     var $children = $temp.last().children().find('.node:first');
    //     if ($children.length) {
    //     $children[0].style.offsetWidth = $children[0].offsetWidth;
    //   }
    //   $children.removeClass('slide-up');
    //   showDescendents(node, visibleLevel--);
    //   });
    // };

    oc.$chartContainer.on('click', '.node', function() {
		console.log($(this).hasClass('node-tree'));
        if($(this).hasClass('node-tree')){
            {{--$.ajax({
                type: "GET",
                url: "{{ route('system.getAjaxSaleUser')}}",
                data: {
                    'User_ID': $(this).attr('id'),
                    'datefrom': $('#datefrom').val(),
                    'dateto': $('#dateto').val(),
                },
                success: function (data) {
                    if(data.status == 200){
                        $('#showInfo #user_userid').val(data.infor.User_ID);
                        $('#showInfo #user_email').val(data.infor.User_Email);
                        $('#showInfo #user_parent').val(data.infor.User_Parent);
                        $('#showInfo #user_address').val(data.infor.User_WalletAddress);
                        $('#left').val(data.trade.left ? '$ '+(number_format(data.trade.left,2)) : '$ 0.00');
                        $('#right').val(data.trade.right ? '$ '+(number_format(data.trade.right,2)) : '$ 0.00');
                    }
                }
            });
            $('#showInfo').modal('show');
			--}}
        }
        else{
            
            if($(this).hasClass('left')){
                $node_side = 0;
            }
            if($(this).hasClass('right')){
                $node_side = 1;
            }
            presenter = $(this).attr('data-parent');
            $('#addChild').modal('show');
            $('#parent').val(sponsor);
            $('#brother').val($(this).attr('data-parent'));
            $('#node_side').val($node_side);
            $('#ref_link').val("{{route('getRegister')}}?parent="+sponsor+"&presenter="+presenter+"&node_side="+$node_side);
            //console.log("{{route('getRegister')}}?ref="+sponsor+"&presenter="+presenter+"&node="+$node_side);
        }


        var $this = $(this);
      
    });

    $('#btn-report-path').on('click', function() {
        $('#chart-container').find('.hidden').removeClass('hidden').end().find('.slide-up, .slide-right, .slide-left, .focused').removeClass('slide-up slide-right slide-left focused');
        var val_search = $('#selected-node').val();
        var get_val = $('#selected-node').val().toUpperCase();
        setTimeout(function(){

            if(val_search == true){
            $('.'+get_val).addClass('focused');
            }else{
                $('#'+get_val).addClass('focused');

            }
            
            var $selected = $('#chart-container').find('.node.focused');
            if ($selected.length) {
                $selected.parents('.nodes').children(':has(.focused)').find('.node:first').each(function(index, superior) {
                if (!$(superior).find('.horizontalEdge:first').closest('table').parent().siblings().is('.hidden')) {
                    $(superior).find('.horizontalEdge:first').trigger('click');
                }
                });
            } else {
                alert('Data does not exist');
            }
        }, 1);
        
    });
    $('#btn-reset').on('click', function() {
        oc.hideChildren(oc.$chart.find('.node:first'));
    //   $('#chart-container').find('.hidden').removeClass('hidden')
    //     .end().find('.slide-up, .slide-right, .slide-left, .focused').removeClass('slide-up slide-right slide-left focused');
      $('#selected-node').val('');
    });
  });
</script>
<script>
	$(document).ready(function () {
        $('.node-empty .title').css({
            'opacity' : 0,
            'height' : 0,
            'padding' : 0,
        });
        $('.node-empty i, .node-empty .content').hide();
        $('.node-empty').append('<img src="img-new/plus.png" alt="" style="width: 50px;">');

    });
       
</script>
@endsection