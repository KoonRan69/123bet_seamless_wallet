@extends('System.Layouts.Master')
@section('title', 'Admin Hiring')
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
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Hiring</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">EGGSBOOK</a></li>
                        <li class="active" style="color:#fff">Hiring</li>
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
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-user"
                                                                        aria-hidden="true"></i> Email</label>
                                                                <input class="form-control" type="text" value="{{request()->input('email')}}"
                                                                    placeholder="Enter email"
                                                                    name="email">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input type='text' name="datefrom" id="datefrom"
                                                                    class="form-control"
                                                                    value="{{request()->input('datefrom')}}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input type='text' name="dateto" id="dateto"
                                                                    class="form-control"
                                                                    value="{{request()->input('dateto')}}" />
                                                            </div>
                                                        </div>
	                                                    <div class="col-md-12 mt-2">
	                                                        <div class="form-group">
	                                                            <div class="form-actions ">
	                                                                <button type="submit"
	                                                                    class="btn-filler btn btn-lg1 btn-success"><i
	                                                                        class="fa fa-search" aria-hidden="true"></i>
	                                                                    Search
	                                                                </button>
	                                                                <a type="" 
                                                                        class="btn-filler btn btn-lg1 btn-danger mr-10"><i
                                                                            class="fa fa-times"
                                                                            aria-hidden="true"></i> Cancel</a>
	                                                                
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
                                            List register hiring</h6>
                                    </div>
                                </div>

                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table id="member-list-table"
                                                    class=" dt-responsive table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">
                                                                ID
                                                            </th>
                                                            <th>
	                                                           Email
                                                            </th>
                                                            <th>
                                                                Name 
                                                            </th>
                                                            <th data-hide="phone">
                                                                Phone
                                                            </th>
                                                            <th data-hide="phone">
                                                                Country
                                                            </th>
                                                            <th data-hide="phone">
                                                                Birthday
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Telegram ID
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Position
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                Worl
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                Resum
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                Date time register
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
	                                                   @foreach($listHiring as $l)
													   	<tr>
														   	<td>{{$l->id}}</td>
							                            	<td>{{$l->email}}</td>
							                            	<td>{{$l->name}}</td>
							                            	<td>{{$l->phone_number }}</td>
							                            	<td>{{$l->country_id }}</td>
							                            	<td>{{$l->birthday }}</td>
							                            	<td>{{$l->telegram_id}}</td>
							                            	<td>
								                            	<span class="">{{$l->position}}</span> 
							                            	</td>
							                            	<td>{{$arrWork[$l->work]}}</td>
							                            	<td>{{$l->resume}}</td>
							                            	<td>{{$l->created_at}}</td>
													   	</tr>
	                                                   @endforeach
                                                    </tbody>
                                                </table>
                                                {{$listHiring->links('System.Layouts.Pagination')}}
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
<script>
    $('#member-list-table').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false
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
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD', clearButton: true , time: false });
    $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD', clearButton: true, time: false });
</script>
@endsection