@extends('System.Layouts.Master')
@section('title', 'Admin Eggs')
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

    .dtp-btn-ok {
        background: #009688;
    }

    .dtp-btn-clear {
        color: black;
    }

    .btn-filler {
        margin-bottom: 10px;
    }

    .pagination {
        float: right;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Eggs</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Eggs</li>
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
                                                                        aria-hidden="true"></i> EGGS ID</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="EGGS ID" name="eggs_id"
                                                                    value="{{request()->input('eggs_id')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> OWNER</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="OWNER" name="owner"
                                                                    value="{{request()->input('owner')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> POOL</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="POOL" name="pool"
                                                                    value="{{request()->input('pool')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    FROM</label>
                                                                <input id="datefrom" type="text" class="form-control"
                                                                    placeholder="yyyy/mm/dd" name="from"
                                                                    value="{{request()->input('from')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    BUY FROM</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Buy from" name="buy_from"
                                                                    value="{{request()->input('buy_from')}}">
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-6">
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
                                                        </div> -->
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
                                                      	<div class="col-md-6">
	                                                        <div class="form-group row">
	                                                        	<label><i class="fa fa-hand-o-down"></i> Hatch</label>
	                                                            <div class="col-sm-12">
	                                                                <select class="form-control c-select" name="can_hatch">
                                                                      	<option value="">--- Select ---</option>
	                                                                    <option value="1" {{request()->input('can_hatch') == "1" && request()->input('can_hatch') != ""? 'selected': ''}}>Can hatch</option>
																		<option value="0" {{request()->input('can_hatch') == "0" && request()->input('can_hatch') != ""? 'selected': ''}}>Cannot hatch</option>
	                                                                </select>
	                                                            </div>
	                                                        </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    {{--                                                        <button type="submit" class="btn btn-lg1 btn-success  mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>--}}
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-primary  mr-10"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search</button>
                                                                    @if(Session('user')->User_Level != 3)
                                                                    <button type="submit"
                                                                        name="export" value="1"
                                                                        class="btn-filler btn btn-success  mr-10"><i
                                                                            class="fa fa-file-excel-o"
                                                                            aria-hidden="true"></i> Export</button>
                                                                    @endif
                                                                    <a href="{{ route('system.admin.getHistoryEggs') }}"
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
                                            Eggs</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table
                                                    class="dt-responsive demo-foo-col-exp table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                ID</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Owner</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Pool</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Buy From</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Buy Date</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Hatches Time</th>

                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Active Time</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Percent</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Type</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                F</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Waiting Active</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Hatch</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($eggs as $eg)
                                                        <tr>
                                                            <td>{{ $eg->ID }}</td>
                                                            <td>{{ $eg->Owner }}</td>
                                                            <td>{{ $eg->Pool }}</td>
                                                            <td>{{ $eg->BuyFrom }}</td>
                                                            <td>{{ date('d-m-Y H:i:s', $eg->BuyDate) }}</td>
                                                            <td>{{ gmdate("H:i:s", $eg->HatchesTime/100) }}</td>
                                                            <td>{{ date('d-m-Y H:i:s', $eg->ActiveTime) }}</td>
                                                            <td>{{ $eg->Percent }}</td>
                                                            <td>{{ $eg->eggsTypes->Name }}</td>
                                                            <td>{{ $eg->F }}</td>
                                                            <td>@if($eg->WaitingActive == 1) Waiting Active @else Active @endif</td>
                                                            <td>{{ $eg->CanHatches? 'Can hatch': 'Cannot hatch' }}</td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
	                                                {{$eggs->appends(request()->input())->links('System.Layouts.Pagination')}}
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
                        name: "Eggs",
                        filename: "Eggs" + new Date().toISOString().replace(/[\-\:\.]/g, "")+".xls",
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