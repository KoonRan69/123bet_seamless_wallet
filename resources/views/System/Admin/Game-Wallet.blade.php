@extends('System.Layouts.Master')
@section('title', 'Wallet History')
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

<link href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
    rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css" rel="stylesheet" />
<link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/style.css" rel="stylesheet">
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<style>
    a:hover {
        cursor: pointer;
    }
    .pagination {
        float: right;
    }
    .mx-auto{
        margin-left: auto;
        margin-right: auto;
    }
    .float-none{
        float: none;
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
                    <h4 class="pull-left page-title">Wallet History</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Wallet History</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
		            <div class="col-md-4">
		                <form method="POST" id="post-bet" action="{{route('system.admin.postInsertBet')}}">
		                    @csrf
		                    <div class="panel panel-default card-view">
		                        <div class="panel-wrapper collapse in">
		                            <div class="panel-body">
		                                <div class="form-wrap">
		                                    <div class="form-body">
		                                        <div class="row">
		                                            <div class="form-group">
		                                                <label for="exampleInputEmail1"><i class="fa fa-users"></i>
		                                                    User ID</label>
		                                                <input type="text" class="form-control" name="user"
		                                                    id="exampleInputEmail1" placeholder="Enter User ID">
		                                            </div>
		                                            <div class="form-group">
		                                                <label for="exampleInputEmail1"><i class="fa fa-money"></i>
		                                                    Amount</label>
		                                                <input type="number" step="any" name="amount"
		                                                    class="form-control" placeholder="Enter amount USD">
		                                            </div>
		                                            <div class="m-t-43">
		                                                <button type="submit" class="btn btn-success"
		                                                    id="btn-deposit"><i class="fa fa-paper-plane"
		                                                        aria-hidden="true"></i>
		                                                    BET</button>
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
                            @csrf
                            <div class="panel panel-default card-view">
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="form-wrap">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10"
                                                                for="exampleInputpwd_1"><i class="fa fa-user"
                                                                    aria-hidden="true"></i> User ID</label>
                                                            <input type="text" name="user_id" class="form-control"
                                                                placeholder="Enter User ID"
                                                                value="{{request()->input('user_id')}}">
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10"
                                                                for="exampleInputpwd_1"><i class="fa fa-users"
                                                                    aria-hidden="true"></i> Type</label>
                                                            <select name="type" class="form-control">
                                                                <option value="">--- Select ---</option>
                                                                @foreach($type as $k => $v)
                                                             
                                                                <option value="{{$k}}"
                                                                    {{(request()->input('type'))? 'selected' : '' }}>
                                                                    {{$v}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-10">
														<div class="form-group">
															<label class="control-label mb-10"><i class="fa fa-hand-o-down" aria-hidden="true"></i> User Level</label>
															<select type="number" class="form-control" name="user_level">
																<option value=""
																{{request()->input('user_level') == '' ? 'selected' : ''}}>--Select--</option>
																<option value="0"
																{{request()->input('user_level') == '0' ? 'selected' : ''}}>Member</option>
																<option value="1"
																{{request()->input('user_level') == '1' ? 'selected' : ''}}>Admin</option>
																<option value="2"
																{{request()->input('user_level') == '2' ? 'selected' : ''}}>Finance</option>
																<option value="3"
																{{request()->input('user_level') == '3' ? 'selected' : ''}}>Support</option>
																<option value="4"
																{{request()->input('user_level') == '4' ? 'selected' : ''}}>Customer</option>
																<option value="5"
																{{request()->input('user_level') == '5' ? 'selected' : ''}}>Bot</option>
															</select>
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
                                                                <button type="submit"
                                                                    class="btn-filler btn btn-lg1 btn-primary"><i
                                                                        class="fa fa-search" aria-hidden="true"></i>
                                                                    Search
                                                                </button>
                                                                <button type="submit" name="export" value="1"
                                                                    class="btn-filler btn btn-lg1 btn-success  mr-10"><i
                                                                        class="fa fa-file-excel-o"
                                                                        aria-hidden="true"></i> Export</button>
                                                                <a href="{{ route('system.admin.getWallet') }}"
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
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <h6 class="panel-title txt-light"><i class="fa fa-history" aria-hidden="true"></i>
                                        HISTORY
                                        WALLET</h6>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <table id="wallet-table"
                                                class="table table-striped table-bordered dt-responsive" cellspacing="0"
                                                width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            ID
                                                        </th>
                                                        <th>
                                                            User
                                                        </th>
                                                        <th>
                                                            Level
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                        <th>
                                                            Type
                                                        </th>
                                                        <th>
                                                            Center
                                                        </th>
                                                        <th>
                                                            Date Time
                                                        </th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   @foreach($gameWallet as $v)
                                                   <tr class="text-{{ ($v->type == 'credit')? 'success':'' }}{{ ($v->type == 'debit')? 'danger':'' }}">
                                                      <td>{{ $v->id }}</td> 
                                                      <td>{{ $v->user }}</td>
                                                      <td>{{ $level[$v->User_Level] }}</td>
                                                      <td>{{ $v->amount }}</td>
                                                      <td>{{ $v->type }}</td>
                                                      <td>{{ $v->center }}</td>
                                                      <td>{{ $v->datetime }}</td>
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
@endsection
@section('script')
<script src="datetime/plugins/momentjs/moment.js"></script>
<script src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
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
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD', time: false, clearButton: true });

  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD', time: false, clearButton: true });
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
    $('#wallet-table').DataTable({
        "bLengthChange": false,
        "pageLength": 50,
        "searching": false,
        "paging": true,
        "order": [0,'desc'],
       
      });
</script>
@endsection