@extends('System.Layouts.Master')
@section('title', 'Admin-Investment')
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
    .btn-filler{
        margin-bottom: 10px;
    }
    .pagination{
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
                    <h4 class="pull-left page-title">Insurance</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">BetCoin</a></li>
                        <li class="active" style="color:#fff">Insurance</li>
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
                            <form method="GET" action="{{route('system.admin.getAdminInsurance')}}">
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
                                                                        aria-hidden="true"></i>  ID</label>
                                                                <input type="name" name="user_id" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter User ID"
                                                                    value="{{request()->input('id')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> User ID</label>
                                                                <input type="name" name="user_id" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter User ID"
                                                                    value="{{request()->input('user_id')}}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1"><i
                                                                        class="fa fa-chevron-down"
                                                                        aria-hidden="true"></i>
                                                                    Status</label>
                                                                <div class="form-group">
                                                                    <select class="form-control" tabindex="1"
                                                                        name="status">
                                                                        <option value="" selected>--- Select ---
                                                                        </option>
                                                                        <option value="1"
                                                                            {{request()->input('status') == 1 ? 'selected' : ''}}>
                                                                            Expired</option>
                                                                        <option value="0"
                                                                            {{request()->input('status') == 0 ? 'selected' : ''}}>
                                                                           Waiting</option>
                                                                        <option value="-1"
                                                                            {{request()->input('status') == -1 ? 'selected' : ''}}>
                                                                            Cancel</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input type='text' name="datefrom" id="datefrom"
                                                                    class="form-control"
                                                                    value="{{request()->input('datefrom')}}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input type='text' name="dateto" id="dateto"
                                                                    class="form-control"
                                                                    value="{{request()->input('dateto')}}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-search" aria-hidden="true"></i>
                                                                        Search
                                                                    </button>
                                                                    <button type="submit" name="export" value="1" class="btn-filler btn btn-lg1 btn-success mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>
                                                                    <a href="{{ route('system.admin.InvestmentList') }}"
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
                                            List Insurance Table</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$getData->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="dt-investment"
                                                    class="table table-striped dt-responsive table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>User ID</th>
                                                            <th>Amount</th>
                                                            <th>Time</th>
                                                            <th>Countries</th>
                                                            <th>Time limit</th>
                                                            <th>Created time</th>
                                                            <th>Expired time</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($getData as $item)
                                                        <tr>
                                                            <td>{{$item->id }}</td>
                                                            <td>{{$item->user_id }}</td>
                                                            <td>{{$item->amount }}</td>
                                                            <td>{{$item->time }}</td>
                                                            <td>{{$item->countries }}</td>
                                                            <td>{{ $item->days }} Days ({{$feeInsur[$item->days]}} Fee) </td>
                                                            <td>{{$item->created_time }}</td>
                                                            <td>{{ $item->expired_time }}</td>
                                                            @if($item->status == 1)
                                                            <td><span class="badge badge-success">Expired  </span></td>
                                                            @elseif($item->status == 0)
                                                            <td><span class="badge badge-warning">Waiting </span></td>
                                                            @else
                                                            <td><span class="badge badge-danger">Canceled</span></td>
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$getData->appends(request()->input())->links('System.Layouts.Pagination')}}
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
<script>
    var e=$("#demo-foo-col-exp");
    $("#demo-input-search2").on("input",function(o){o.preventDefault(),e.trigger("footable_filter",{filter:$(this).val()})})
</script>


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
        var currentDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        $('#dt-investment').DataTable({
          "bLengthChange": false,
        "searching": false,
          "paging": false,
          "order": [0,'desc']
      });
</script>
@endsection