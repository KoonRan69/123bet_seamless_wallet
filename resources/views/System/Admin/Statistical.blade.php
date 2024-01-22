@extends('System.Layouts.Master')
@section('title', 'Admin Statistic')
@section('css')
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
	.table > tfoot > tr > th, .table > thead > tr > th {
    color: #fff!important;
}
    .dtp-btn-ok {
        background: #009688;
    }

    .dtp-btn-clear {
        color: black;
    }

    .btn-filler {
        margin-bottom: 10px;
    }

    
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Statistical</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Statistical</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- /Title -->
                    <div class="row">
                        <div class="col-md-12">
                            <form method="GET" action="">
                                <div class="panel panel-default card-view">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> User ID</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="User ID" name="User_ID"
                                                                    value="{{request()->input('User_ID')}}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> User Tree</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="User Tree" name="User_Tree"
                                                                    value="{{request()->input('User_Tree')}}">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input id="datefrom" type="text" class="form-control"
                                                                    placeholder="yyyy/mm/dd" name="from"
                                                                    value="{{request()->input('from')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1"><i
                                                                        class="fa fa-chevron-down"
                                                                        aria-hidden="true"></i>
                                                                    Level</label>
                                                                <select type="number" class="form-control"
                                                                    name="User_Level">
                                                                    <option value="" selected>--- Select ---</option>
                                                                    <option value="0"
                                                                        {{request()->input('User_Level') == '0' ? 'selected' : ''}}>
                                                                        User</option>
                                                                    <option value="1"
                                                                        {{request()->input('User_Level') == '1' ? 'selected' : ''}}>
                                                                        Admin</option>
                                                                    <option value="2"
                                                                        {{request()->input('User_Level') == '2' ? 'selected' : ''}}>
                                                                        Finance</option>
                                                                    <option value="4"
                                                                        {{request()->input('User_Level') == '4' ? 'selected' : ''}}>
                                                                        Customer</option>
                                                                    <option value="3"
                                                                        {{request()->input('User_Level') == '3' ? 'selected' : ''}}>
                                                                        Support</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input id="dateto" type="text" class="form-control"
                                                                    placeholder="yyyy/mm/dd" name="to"
                                                                    value="{{request()->input('to')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <!-- {{--                                                        <button type="submit" class="btn btn-lg1 btn-success  mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>--}} -->
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-primary  mr-10"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search</button>
                                                                    @if(Session('user')->User_Level != 3)
                                                                    <button type="button" id="exportTest"
                                                                        class="btn-filler btn btn-success  mr-10"><i
                                                                            class="fa fa-file-excel-o"
                                                                            aria-hidden="true"></i> Export</button>
                                                                    @endif
                                                                    <a href="{{ route('system.admin.getStatistical') }}"
                                                                        class="btn-filler btn btn-default mr-10">Cancel</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            Statistical</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table
                                                    class="dt-responsive demo-foo-col-exp table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%"
                                                    style="color: #333333"
                                                    >
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="4" class=""
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;background: linear-gradient(to top, #f5b61a, #f58345)!important;">
                                                                User
                                                            </th>
                                                            <th class=""style="text-align: center;vertical-align: middle; border: 1px solid; background: #FABF8F !important;"></th>
                                                            <th style="text-align: center;vertical-align: middle; border: 1px solid; background: #FABF8F !important;" class="border-right"></th>
                                                            <th colspan="4" rowspan="2"
                                                                style="text-align:center; border: 1px solid; background: #31859B !important;"
                                                                class="border-right">
                                                                Deposit</th>
                                                           

                                                            <th colspan="6" class="border-right"
                                                            style="text-align:center; border: 1px solid; background: #31859B !important;">
                                                                Gold</th>

                                                            <th colspan="14" class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">
                                                                Doanh số</th>

                                                            <th colspan="6" class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Hoa hồng</th>

                                                            <th colspan="2" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Chuyển nội bộ</th>
                                                            <th colspan="6" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Market</th>
                                                            <th colspan="2" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Mini game</th>
                                                            <th colspan="2" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Bán gold cho hệ thống</th>
                                                            <th colspan="3" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">
                                                                Bán trứng cho hệ thống</th>
                                                            <th rowspan="2" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">
                                                                Rút (Chưa bao gồm phí)(EUSD)</th>
                                                            <th colspan="5" class="border-right"
                                                            style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">
                                                                Phí</th>
                                                        </tr>
                                                        <tr>
                                                            <!-- 							balance -->
		                                                    <th colspan="2" class="text-right border-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FABF8F !important;">Balance</th>
		                                                    <th colspan="2" class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Buy Gold</th>
		                                                    <th colspan="4" class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Gold thưởng</th>
                                                            <!-- 							deposit -->
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Buy Egg</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Buy Food</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Active fish</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Buy pool</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Buy Item</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Active Egg</th>
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Buy gift code</th>
                                                            <!-- 							withdraw -->
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Commission Buy Egg</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Commission Buy Gold</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Egg</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Commission Active Grow</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Commission Buy Item</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Commission buy Gift code</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Chuyển đi</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Nhận</th>
                                                            <th colspan="3"  class="text-right border-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Buy Egg from market</th>
                                                         
                                                            <!-- 							transfer -->
                                                            <th colspan="3" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Sell egg from market</th>
                                                            <th class="text-right border-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Deposit AG Game</th>
                                                            <!-- 						GIVE	transfer -->
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Withdraw AG Game</th>
                                                            <th colspan="2" class="text-right border-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Sell Gold</th>
                                                            <!-- 							Investment -->
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Số EUSD thu được</th>
                                                            <th rowspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Số trứng đã bán</th>
                                                            <!-- 							Interest -->
                                                            <th rowspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Phí rút</th>
                                                            <!-- 							Direct -->
                                                            <th rowspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Phí chuyển nội bộ</th>
                                                            <th rowspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Phí thu hồi trứng</th>
                                                            <!-- 							Affiliate -->
                                                            <th colspan="2" class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Phí giao dịch Market</th>
                                                        </tr>
                                                        <tr>
                                                            <!-- 							balance -->
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FABF8F !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FABF8F !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">ETH</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">USDT</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">RBD -> EUSD</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">EUSD tương đương</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Gold</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Harvest Hippocampus</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Mission Success</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Lucky Spin</th>
		                                                    <th class="text-right" style="text-align:center; border: 1px solid; background: #31859B !important;">Use gift code</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Egg</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">GOLD</th>
                                                            <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">Gift code</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Trứng nhận được</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Trứng chuyển đi </th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">GOLD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">Gold Tương Đương</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">EBP Tương Đương</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #C00000 !important;">USDT</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">EUSD</th>
		                                                    <th class="text-right" style="text-align: center;vertical-align: middle; border: 1px solid; background: #FFC003 !important;">GOLD</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($Statistic as $statistic)
                                                        <tr>
                                                            <td>Total</td>
                                                            <td class="text-right">{{ $statistic->user_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->user_gold }}</td>
                                                            <td class="text-right">{{ $statistic->deposit_eth }}</td>
                                                            <td class="text-right">{{ $statistic->deposit_usdt }}</td>
                                                            <td class="text-right">{{ $statistic->deposit_rbd }}</td>
                                                            <td class="text-right">{{ $statistic->deposit_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_gold_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_gold }}</td>
                                                            <td class="text-right">{{ $statistic->gold_reward_havest_hippo }}</td>
                                                            <td class="text-right">{{ $statistic->gold_reward_mission_success }}</td>
                                                            <td class="text-right">{{ $statistic->gold_reward_lucky_spin }}</td>
                                                            <td class="text-right">{{ $statistic->use_gift_code }}</td>
                                                            <td class="text-right">{{ $statistic->buy_egg_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->count_egg_buy }}</td>
                                                            <td class="text-right">{{ $statistic->buy_food_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_food_gold }}</td>
                                                            <td class="text-right">{{ $statistic->active_fish_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->active_fish_gold }}</td>
                                                            <td class="text-right">{{ $statistic->buy_pool_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_pool_gold }}</td>
                                                            <td class="text-right">{{ $statistic->buy_item_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_item_gold }}</td>
                                                            <td class="text-right">{{ $statistic->active_egg_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->active_egg_gold }}</td>
                                                            <td class="text-right">{{ $statistic->buy_gift_code_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->count_buy_gift_code_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->commission_buy_egg_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->commission_buy_gold }}</td>
                                                            <td class="text-right">{{ $statistic->commission_active_egg_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->commission_active_grow_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->commission_buy_item }}</td>
                                                            <td class="text-right">{{ $statistic->commission_buy_gift_code }}</td>
                                                            <td class="text-right">{{ $statistic->transfer_to }}</td>
                                                            <td class="text-right">{{ $statistic->received_from }}</td>
                                                            <td class="text-right">{{ $statistic->count_buy_egg_from_market }}</td>
                                                            <td class="text-right">{{ $statistic->buy_egg_from_market_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->buy_egg_from_market_gold }}</td>
                                                            <td class="text-right">{{ $statistic->count_sell_egg_from_market }}</td>
                                                            <td class="text-right">{{ $statistic->sell_egg_from_market_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->sell_egg_from_market_gold }}</td>
                                                            <td class="text-right">{{ $statistic->deposit_ag_game }}</td>
                                                            <td class="text-right">{{ $statistic->withdraw_ag_game }}</td>
                                                            <td class="text-right">{{ $statistic->sell_gold_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->sell_gold }}</td>
                                                            <td class="text-right">{{ $statistic->sell_egg_system_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->sell_egg_system_ebp }}</td>
                                                            <td class="text-right">{{ $statistic->count_sell_egg_system }}</td>
                                                            <td class="text-right">{{ $statistic->withdraw_without_fee }}</td>
                                                            <td class="text-right">{{ $statistic->fee_withdraw }}</td>
                                                            <td class="text-right">{{ $statistic->fee_transfer }}</td>
                                                            <td class="text-right">0</td>
                                                            <td class="text-right">{{ $statistic->fee_market_trading_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->fee_market_trading_gold }}</td>
                                                        </tr>
                                                        @endforeach

                                                        @foreach($staticUser as $value)
                                                        <tr>
                                                            <td>{{$value->Money_User}}</td>
                                                            <td class="text-right">{{ $value->user_eusd }}</td>
                                                            <td class="text-right">{{ $value->user_gold }}</td>
                                                            <td class="text-right">{{ $value->deposit_eth }}</td>
                                                            <td class="text-right">{{ $value->deposit_usdt }}</td>
                                                            <td class="text-right">{{ $value->deposit_rbd }}</td>
                                                            <td class="text-right">{{ $value->deposit_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_gold_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_gold }}</td>
                                                            <td class="text-right">{{ $value->gold_reward_havest_hippo }}</td>
                                                            <td class="text-right">{{ $value->gold_reward_mission_success }}</td>
                                                            <td class="text-right">{{ $value->gold_reward_lucky_spin }}</td>
                                                            <td class="text-right">{{ $statistic->use_gift_code }}</td>
                                                            <td class="text-right">{{ $value->buy_egg_eusd }}</td>
                                                            <td class="text-right">{{ $value->count_egg_buy }}</td>
                                                            <td class="text-right">{{ $value->buy_food_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_food_gold }}</td>
                                                            <td class="text-right">{{ $value->active_fish_eusd }}</td>
                                                            <td class="text-right">{{ $value->active_fish_gold }}</td>
                                                            <td class="text-right">{{ $value->buy_pool_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_pool_gold }}</td>
                                                            <td class="text-right">{{ $value->buy_item_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_item_gold }}</td>
                                                            <td class="text-right">{{ $value->active_egg_eusd }}</td>
                                                            <td class="text-right">{{ $value->active_egg_gold }}</td>
                                                            <td class="text-right">{{ $statistic->buy_gift_code_eusd }}</td>
                                                            <td class="text-right">{{ $statistic->count_buy_gift_code_eusd }}</td>
                                                            <td class="text-right">{{ $value->commission_buy_egg_eusd }}</td>
                                                            <td class="text-right">{{ $value->commission_buy_gold }}</td>
                                                            <td class="text-right">{{ $value->commission_active_egg_eusd }}</td>
                                                            <td class="text-right">{{ $value->commission_active_grow_eusd }}</td>
                                                            <td class="text-right">{{ $value->commission_buy_item }}</td>
                                                            <td class="text-right">{{ $statistic->commission_buy_gift_code }}</td>
                                                            <td class="text-right">{{ $value->transfer_to }}</td>
                                                            <td class="text-right">{{ $value->received_from }}</td>
                                                            <td class="text-right">{{ $value->count_buy_egg_from_market }}</td>
                                                            <td class="text-right">{{ $value->buy_egg_from_market_eusd }}</td>
                                                            <td class="text-right">{{ $value->buy_egg_from_market_gold }}</td>
                                                            <td class="text-right">{{ $value->count_sell_egg_from_market }}</td>
                                                            <td class="text-right">{{ $value->sell_egg_from_market_eusd }}</td>
                                                            <td class="text-right">{{ $value->sell_egg_from_market_gold }}</td>
                                                            <td class="text-right">{{ $value->deposit_ag_game }}</td>
                                                            <td class="text-right">{{ $value->withdraw_ag_game }}</td>
                                                            <td class="text-right">{{ $value->sell_gold_eusd }}</td>
                                                            <td class="text-right">{{ $value->sell_gold }}</td>
                                                            <td class="text-right">{{ $value->sell_egg_system_eusd }}</td>
                                                            <td class="text-right">{{ $value->sell_egg_system_ebp }}</td>
                                                            <td class="text-right">{{ $value->count_sell_egg_system }}</td>
                                                            <td class="text-right">{{ $value->withdraw_without_fee }}</td>
                                                            <td class="text-right">{{ $value->fee_withdraw }}</td>
                                                            <td class="text-right">{{ $value->fee_transfer }}</td>
                                                            <td class="text-right">0</td>
                                                            <td class="text-right">{{ $value->fee_market_trading_eusd }}</td>
                                                            <td class="text-right">{{ $value->fee_market_trading_gold }}</td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
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
</div>
@endsection
@section('script')
<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/jszip.min.js"></script>
<script src="assets/plugins/datatables/pdfmake.min.js"></script>
<script src="assets/plugins/datatables/vfs_fonts.js"></script>
<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables/buttons.print.min.js"></script>
<script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
<script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>
<script>
    var today = new Date();
    var currentTime = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

    $('#dt-statistical').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false
    });
</script>

<script src="assets/jquery-table2excel/dist/jquery.table2excel.min.js"></script>

<script>
    $(function() {
                $('#exportTest').click(function(){
                    $(".demo-foo-col-exp").table2excel({
                        exclude: ".noExl",
                        name: "Statistical",
                        filename: "Statistical" + new Date().toISOString().replace(/[\-\:\.]/g, "")+".xls",
                        fileext: ".xls",
                        exclude_img: true,
                        exclude_links: true,
                        exclude_inputs: true
                    });
                    
                });
            });
</script>

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script
    src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
</script>
<script src="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker.min.js">
</script>
<script src="datetime/plugins/bootstrap-datetime-picker/js/bootstrap-datetimepicker.js">
</script>
<script src="datetime/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js">
</script>
<script src="datetime/plugins/bootstrap-daterange/daterangepicker.js"></script>
<script src="datetime/plugins/clockface/js/clockface.js"></script>
<script src="datetime/plugins/clockpicker/clockpicker.js"></script>

<script src="datetime/assets/js/pages/forms/date-time-picker-custom.js"></script>
<script>
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });
        
      $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });
</script>
@endsection