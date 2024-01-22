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
                        <div class="col-md-4">
                            <form method="POST" action="{{route('system.admin.postTransferEgg')}}">
                                @csrf
                                <div class="panel panel-default card-view">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Balance Eggs</label>
                                                                <input type="text" class="form-control" value="{{$balance['Egg']}} EGG" readonly="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> User Give</label>
                                                                <input type="text" class="form-control" id="user_give"
                                                                    placeholder="Enter UserID Or Email User Give Egg" name="user">
                                                                <div><b class="alert-custom"></b></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Quantity (Optional)</label>
                                                                <input type="number" class="form-control" min="0"
                                                                    placeholder="Enter Eggs Quantity" name="quantity">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Authentication Code</label>
                                                                <input type="text" class="form-control" 
                                                                    placeholder="Enter Auth Code" name="otp">
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> EGGS ID (Optional)</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="EGGS ID" name="eggs_id"
                                                                    value="{{request()->input('eggs_id')}}">
                                                            </div>
                                                        </div> -->
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-primary  mr-10"><i
                                                                            class="fa fa-send" aria-hidden="true"></i>
                                                                        Transfer</button>
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
                        <div class="col-md-8">
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
                                                                        aria-hidden="true"></i> User Give</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="User Give" name="user_give"
                                                                    value="{{request()->input('user_give')}}">
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
                                                                    To</label>
                                                                <input id="dateto" type="text" class="form-control"
                                                                    placeholder="yyyy/mm/dd" name="to"
                                                                    value="{{request()->input('to')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-primary  mr-10"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search</button>
                                                                    <a href="{{ route('system.admin.getEggsTransfer') }}"
                                                                        class="btn-filler btn btn-default mr-10">Cancel</a>
                                                                    <button type="submit"
                                                                    name="export" value="1"
                                                                    class="btn-filler btn btn-success  mr-10"><i
                                                                        class="fa fa-file-excel-o"
                                                                        aria-hidden="true"></i> Export</button>
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
                                                                #</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                User Transfer</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                User Give</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Comment</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Date</th>
                                                            <th class="border-right"
                                                                style="text-align: center;vertical-align: middle; border: 1px solid;">
                                                                Status</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($eggs as $eg)
                                                        <tr>
                                                            <td>{{ $eg->Log_ID }}</td>
                                                            <td>{{ $eg->Log_User }}</td>
                                                            <td>{{ substr($eg->Log_Comment, -6) }}</td>
                                                            <td>{{ $eg->Log_Comment }}</td>
                                                            <td>{{ $eg->Log_CreatedAt }}</td>
                                                            <td>{{ $eg->Log_Status == 1 ? 'Success' : 'Cancel' }}</td>
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
    $(document).ready(function(){
        $('#user_give').blur(function(){
            _user = $(this).val();
            $.ajax({
            type: "GET",
            url: "{{ route('system.getAjaxUser')}}",
            data: {
                user : _user,
            },
            success: function (data) {
                //remove
                $('.alert-custom').html('<span class="text-'+data.class+'">'+data.message+'</span>');
            }
            })
        });
    });
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