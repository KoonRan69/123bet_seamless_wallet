@extends('System.Layouts.Master')
@section('title', 'Admin package gift code')
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
                    <h4 class="pull-left page-title">Package Gift Code</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">EGGSBOOK</a></li>
                        <li class="active" style="color:#fff">Package Gift Code</li>
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
                            <form method="POST" action="{{route('admin.postAddPackageGiftCode')}}">
	                            @csrf
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
                                                                        aria-hidden="true"></i> Name Gift Code</label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Enter Name Gift Code"
                                                                    name="name_giftcode">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Quantity</label>
                                                                <input type="number" class="form-control"
                                                                    placeholder="Enter Amount" name="quantity" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Amount</label>
                                                                <input type="number" class="form-control" step="0.00001"
                                                                    placeholder="Enter Amount" name="amount" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Price EUSD</label>
                                                                <input type="number" class="form-control" step="0.00001"
                                                                    placeholder="Enter Amount" name="priceEUSD" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Price GOLD</label>
                                                                <input type="number" class="form-control" step="0.00001"
                                                                    placeholder="Enter Amount" name="priceGOLD" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
	                                                        <div class="form-group row">
	                                                        	<label><i class="fa fa-hand-o-down"></i> Type</label>
	                                                            <div class="col-sm-12">
	                                                                <select class="form-control c-select" name="type">
	                                                                    <option value="9" selected="">GOLD</option>
	
	                                                                </select>
	                                                            </div>
	                                                        </div>
                                                        </div>
	                                                    <div class="col-md-12 mt-2">
	                                                        <div class="form-group">
	                                                            <div class="form-actions ">
	                                                                <button type="submit"
	                                                                    class="btn-filler btn btn-lg1 btn-success"><i
	                                                                        class="fa fa-plus" aria-hidden="true"></i>
	                                                                    Add
	                                                                </button>
	                                                                <a href="{{ route('admin.getPackageGiftCode') }}"
	                                                                    class="btn-filler btn btn-danger mr-10"><i
	                                                                        class="fa fa-times" aria-hidden="true"></i> Cancel</a>
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
                                            List package gift code</h6>
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
	                                                           User Creator
                                                            </th>
                                                            <th>
                                                                Name Package 
                                                            </th>
                                                            <th data-hide="phone">
                                                                Amount
                                                            </th>
                                                            <th data-hide="phone">
                                                                Quantity
                                                            </th>
                                                            <th data-hide="phone">
                                                                Price EUSD
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Price GOLD
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Type
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                Date Created
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
	                                                   @foreach($listPackage as $l)
													   	<tr>
														   	<td>{{$l->Package_ID}}</td>
							                            	<td>{{$l->Package_User}}</td>
							                            	<td>{{$l->Package_Name}}</td>
							                            	<td>{{$l->Package_Amount }}</td>
							                            	<td>{{$l->Package_Quantity }}</td>
							                            	<td>{{number_format($l->Package_PriceEUSD, 2) }} EUSD</td>
							                            	<td>{{number_format($l->Package_PriceGOLD, 2) }} GOLD</td>
							                            	<td>
								                            	<span class="">{{$arrType[$l->Package_Type]}}</span> 
							                            	</td>
							                            	<td>{{$l->Created_at}}</td>
							                            	<td>
								                            	<a href="{{ route('admin.getEditPackageGiftCode', $l->Package_ID) }}"
                                                                    class="btn btn-success btn-xs waves-effect waves-light"><i
                                                                        class="fa fa-penci"> Edit</i></a>
                                                                <a href="{{ route('admin.getDelePackageGiftCode', $l->Package_ID) }}"
                                                                    class="btn btn-danger btn-xs ml-10 waves-effect waves-light"><i
                                                                        class="fa fa-times"> Detele</i></a>
							                            	</td>
													   	</tr>
	                                                   @endforeach
                                                    </tbody>
                                                </table>
                                                {{$listPackage->links('System.Layouts.Pagination')}}
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
    $('#datetime').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
@endsection