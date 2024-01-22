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
                    <h4 class="pull-left page-title">List Egg Rate</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">EGGSBOOK</a></li>
                        <li class="active" style="color:#fff">List Egg Rate</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">

                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                
                    
                            <form method="GET" action="">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> FISH ID</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="FISH ID" name="fish_id"
                                                                    value="{{request()->input('fish_id')}}">
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
                                                                    <a href="{{ route('system.admin.listEggRate') }}"
                                                                        class="btn-filler btn btn-default mr-10">Cancel</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                            </form>

                            <form action="{{route('system.admin.postChangeTableID')}}" method="post">
                                @csrf
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <h3>Change table ID:</h3>
                                        <div class="row">
                                        <input type="hidden" class="form-control"
                                        placeholder="Fish id" name="fish_id"
                                        value="{{$eggFind->fish_id ?? ''}}">
                                        <input type="hidden" class="form-control"
                                        placeholder="Fish id" name="user"
                                        value="{{$eggFind->user ?? ''}}">
                                        <input type="hidden" class="form-control"
                                        placeholder="Fish id" name="level_fish"
                                        value="{{$eggFind->level_fish ?? ''}}">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label mb-10"
                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                            aria-hidden="true"></i> Fish ID</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Fish id" name="fish_id"
                                                        value="{{$eggFind->fish_id ?? ''}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label mb-10"
                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                            aria-hidden="true"></i> User</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="User" name="user"
                                                        value="{{$eggFind->user ?? ''}}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label mb-10"
                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                            aria-hidden="true"></i> Fish level</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Fish level" name="level_fish"
                                                        value="{{$eggFind->level_fish ?? ''}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label mb-10"
                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                            aria-hidden="true"></i> From ID</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="From ID" name="from_id"
                                                        value="{{$eggFind->id ?? ''}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label mb-10"
                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                            aria-hidden="true"></i> To ID</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="To ID" name="to_id"
                                                        value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        @if($eggFind)
                                        <button type="submit"
                                        class="btn-filler btn btn-primary  mr-10">
                                        Change</button>
                                        @endif
                                    </div>
                                </div>

                            </form>
                       

                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$eggFail->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="member-list-table"
                                                    class="dt-responsive demo-foo-col-exp table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">
                                                                ID
                                                            </th>
                                                            <th>
	                                                           User
                                                            </th>
                                                            <th>
                                                                Fish ID
                                                            </th>
                                                            <th data-hide="phone">
                                                                Level Fish
                                                            </th>
                                                            <th data-hide="phone">
                                                                Egg fail
                                                            </th>
                                                            <th data-hide="phone">
                                                                Egg success
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Created add
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Eggbreed time
                                                            </th>
                                                            <th data-hide="phone, tablet">
                                                                Function
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
	                                                   @foreach($eggFail as $key => $egg)
													   	<tr>
														   	<td>{{$egg->id}}</td>
							                            	<td>{{$egg->user}}</td>
							                            	<td>{{$egg->fish_id}}</td>
							                            	<td>{{$egg->level_fish }}</td>
							                            	<td>{{$egg->eggs_fail }}</td>
							                            	<td>{{$egg->eggs_success }}</td>
							                            	<td>{{$egg->created_at }}</td>
							                            	<td>{{$egg->egg_breed_time }}</td>
                                                            <td><a href="{{route('system.admin.listEggRate', ['id' => $egg->id])}}">
                                                                <button 
                                                                    type="button"
                                                                    class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2" 
                                                                    {{!$egg->user? 'disabled': ''}}
                                                                >Change</button></a></td>
													   	</tr>
	                                                   @endforeach
                                                    </tbody>
                                                </table>
                                                {{$eggFail->appends(request()->input())->links('System.Layouts.Pagination')}}
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