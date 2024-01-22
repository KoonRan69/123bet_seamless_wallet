@extends('System.Layouts.Master')
@section('title', 'Admin Setting Insurance')
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
                    <h4 class="pull-left page-title">Setting Insurance</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">BetCoin</a></li>
                        <li class="active" style="color:#fff">Setting Insurance</li>
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
                            <form method="POST" action="{{route('system.admin.postAdminInsuranceGame')}}">
                              	@csrf
                                <div class="panel panel-default card-view">
                                  <div class="panel-heading">
                                      <div class="">
                                          <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                              Add Game</h6>
                                      </div>
                                  </div>
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Name Game</label>
                                                                <input type="text" name="game_name" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter game"
                                                                    value="{{request()->input('game_name')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                                                        Save
                                                                    </button>
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
                        <div class="col-md-4">
                            <form method="POST" action="{{route('system.admin.postAdminInsuranceTime')}}">
                              	@csrf
                                <div class="panel panel-default card-view">
                                  <div class="panel-heading">
                                      <div class="">
                                          <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                              Add Time Zoom</h6>
                                      </div>
                                  </div>
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Time</label>
                                                                <input type="text" name="time" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter Time start EX: 19:00 - 21:00"
                                                                    value="{{request()->input('time')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                                                        Save
                                                                    </button>
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
                        <div class="col-md-4">
                            <form method="POST" action="{{route('system.admin.postAdminInsuranceMin')}}">
                              	@csrf
                                <div class="panel panel-default card-view">
                                  <div class="panel-heading">
                                      <div class="">
                                          <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                              Add min insurance</h6>
                                      </div>
                                  </div>
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                          	<span style="color: #F44336; font-size: 24px;display: block;margin: 0px auto 15px;text-align: center">Current min: {{number_format($min->Min, 2)}}</span>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Min</label>
                                                                <input type="text" name="min" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter min"
                                                                    value="{{request()->input('time')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                                                        Save
                                                                    </button>
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
						<div class="col-md-6">
                            <form method="POST" action="{{route('system.admin.postAdminInsuranCountries')}}">
                              	@csrf
                                <div class="panel panel-default card-view">
                                  <div class="panel-heading">
                                      <div class="">
                                          <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                              Add Countries</h6>
                                      </div>
                                  </div>
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Time</label>
                                                                <select class="form-control" name="countries">
                                                                  @foreach($countries as $l)
                                                                  <option value="{{$l->Countries_Name}}">{{$l->Countries_Name}}</option>
                                                                  @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                                                        Save
                                                                    </button>
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
						<div class="col-md-6">
                            <form method="POST" action="{{route('system.admin.postAdminInsuranDate')}}">
                              	@csrf
                                <div class="panel panel-default card-view">
                                  <div class="panel-heading">
                                      <div class="">
                                          <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                              Add Time Limit and Fee</h6>
                                      </div>
                                  </div>
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Date</label>
                                                                <input type="text" name="date" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter date"
                                                                    value="{{request()->input('date')}}">
                                                            </div>
                                                      	</div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1">  Fee(%)</label>
                                                                <input type="text" name="fee" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter fee"
                                                                    value="{{request()->input('fee')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit" class="btn-filler btn btn-lg1 btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                                                                        Save
                                                                    </button>
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
                        <div class="col-md-6">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List Game</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table id="dt-investment"
                                                    class="table table-striped dt-responsive table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Game name</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($list_game as $item)
                                                        <tr>
                                                            <td>{{$item->Promotion_Game_ID }}</td>
                                                            <td>{{$item->Promotion_Game_Name }}</td>
                                                            @if($item->Promotion_Game_Status == 1)
                                                            <td><span class="badge badge-success">Confirm  </span></td>
                                                            @elseif($item->status == 0)
                                                            <td><span class="badge badge-warning">Waiting </span></td>
                                                            @else
                                                            <td><span class="badge badge-danger">Canceled</span></td>
                                                            @endif
                                                            <td>
                                                              <button type="button" data-toggle="modal" data-id="{{$item->Promotion_Game_ID }}" 
                                                                      data-name="{{$item->Promotion_Game_Name }}"
                                                                    data-target="#edit_game" class="btn-edit-game btn btn-lg1 btn-warning">
                                                                Edit
                                                              </button>
                                                              <a type="submit" href="{{route('system.admin.getAdminInsuranceDeleGame', $item->Promotion_Game_ID)}}" class="btn btn-lg1 btn-danger">
                                                                Delete
                                                              </a>
                                                          	</td>
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
                      
                      
                      	<div class="col-md-6">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List Time Zoom</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table id="dt-investment"
                                                    class="table table-striped dt-responsive table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Time</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($list_time as $item)
                                                        <tr>
                                                            <td>{{$item->id }}</td>
                                                            <td>{{$item->time}}</td>
                                                            @if($item->status == 1)
                                                            <td><span class="badge badge-success">Confirm  </span></td>
                                                            @elseif($item->status == 0)
                                                            <td><span class="badge badge-warning">Waiting </span></td>
                                                            @else
                                                            <td><span class="badge badge-danger">Canceled</span></td>
                                                            @endif
                                                            <td>
                                                              <button type="button" data-toggle="modal" data-id="{{$item->id }}" 
                                                                      data-time="{{$item->time }}"
                                                                    data-target="#edit_time" class="btn-edit-time btn btn-lg1 btn-warning">
                                                                Edit
                                                              </button>
                                                              <a type="submit" href="{{route('system.admin.getAdminInsuranceDeleTime', $item->id)}}" class="btn btn-lg1 btn-danger">
                                                                Delete
                                                              </a>
                                                          	</td>
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
                      	<div class="col-md-6">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List Countries</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table id="dt-investment"
                                                    class="table table-striped dt-responsive table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Countries</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($list_countries as $item)
                                                        <tr>
                                                            <td>{{$item->ID }}</td>
                                                            <td>
                                                              <select onchange="{{ Session('user')->User_Level != 10 ? 'location=this.value' : '' }}" 
                                                                      class="form-control" name="countries">
                                                                  @foreach($countries as $k=>$l)
                                                                  <option value="{{route('system.admin.getAdminInsuranceEditCountries', [$item->ID, $l->Countries_Name])}}" {{$l->Countries_Name == $item->Countries_id ? "selected" : ""}}>{{$l->Countries_Name}}</option>
                                                                  @endforeach
                                                                </select>
                                                            </td>
                                                            @if($item->Status  == 1)
                                                            <td><span class="badge badge-success">Confirm  </span></td>
                                                            @elseif($item->Status  == 0)
                                                            <td><span class="badge badge-warning">Waiting </span></td>
                                                            @else
                                                            <td><span class="badge badge-danger">Canceled</span></td>
                                                            @endif
                                                            <td>
                                                              <a type="submit" href="{{route('system.admin.getAdminInsuranceDeleCountries', $item->ID)}}" class="btn btn-lg1 btn-danger">
                                                                Delete
                                                              </a>
                                                          	</td>
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
                      	<div class="col-md-6">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List Date and fee</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="clear:both"></div>
                                                <table id="dt-investment"
                                                    class="table table-striped dt-responsive table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Date</th>
                                                            <th>Fee(%)</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($list_date as $item)
                                                        <tr>
                                                            <td>{{$item->ID }}</td>
                                                            <td>{{$item->Date}}</td>
                                                            <td>{{$item->Fee * 100}}</td>
                                                            @if($item->Status == 1)
                                                            <td><span class="badge badge-success">Confirm  </span></td>
                                                            @elseif($item->Status == 0)
                                                            <td><span class="badge badge-warning">Waiting </span></td>
                                                            @else
                                                            <td><span class="badge badge-danger">Canceled</span></td>
                                                            @endif
                                                            <td>
                                                              <button type="button" data-toggle="modal" data-id="{{$item->ID }}" 
                                                                      data-date="{{$item->Date }}" data-fee="{{$item->Fee }}"
                                                                    data-target="#edit_date" class="btn-edit-date btn btn-lg1 btn-warning">
                                                                Edit
                                                              </button>
                                                              <a type="submit" href="{{route('system.admin.getAdminInsuranceDeleDate', $item->ID)}}" class="btn btn-lg1 btn-danger">
                                                                Delete
                                                              </a>
                                                          	</td>
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
<div id="edit_game" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-profile-header"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('system.admin.postAdminEditInsurGame')}}">
                    @csrf
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_01"
                           style="color: #0088ce">ID</label>

                      <input type="text" class="form-control " name="id"
                             id="game_id" placeholder="" value="" readonly>
                  </div>
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_02"
                           style="color: #0088ce">Name</label>

                      <input type="text" class="form-control " name="name"
                             id="game_name" placeholder="" value="">
                  </div>
                  <div class="">
                      <button type="submit" class="btn btn-success" >Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
            </div>
        </div>

    </div>
</div>
<div id="edit_date" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-profile-header"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('system.admin.postAdminEditInsurDate')}}">
                    @csrf
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_01"
                           style="color: #0088ce">ID</label>

                      <input type="text" class="form-control " name="id"
                             id="date_id" placeholder="" value="" readonly>
                  </div>
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_02"
                           style="color: #0088ce">Date</label>

                      <input type="text" class="form-control " name="date"
                             id="date_date" placeholder="" value="">
                  </div>
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_02"
                           style="color: #0088ce">Feee</label>

                      <input type="text" class="form-control " name="fee"
                             id="date_fee" placeholder="" value="">
                  </div>
                  <div class="">
                      <button type="submit" class="btn btn-success" >Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
            </div>
        </div>

    </div>
</div>

<div id="edit_time" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-profile-header"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('system.admin.postAdminEditInsurTime')}}">
                    @csrf
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_01"
                           style="color: #0088ce">ID</label>

                      <input type="text" class="form-control " name="id"
                             id="time_id" placeholder="" value="" readonly>
                  </div>
                  <div class="form-group">
                    <label class="control-label mb-10" for="exampleInputuname_02"
                           style="color: #0088ce">Time</label>

                      <input type="text" class="form-control " name="time_start"
                             id="time_start" placeholder="" value="">
                  </div>
                  <div class="">
                      <button type="submit" class="btn btn-success" >Save</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  </div>
                </form>
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
  	$('.btn-edit-game').click(function(){
      _id = $(this).data('id');
      _name = $(this).data('name');
      $('#game_id').val(_id);
      $('#game_name').val(_name);
    });
  
  	$('.btn-edit-time').click(function(){
      _id = $(this).data('id');
      _game = $(this).data('game');
      _time = $(this).data('time');
      $('#time_id').val(_id);
      $('#time_start').val(_time);
    });
  
  	$('.btn-edit-date').click(function(){
      _id = $(this).data('id');
      _date = $(this).data('date');
      _fee = $(this).data('fee');
      _fee = _fee * 100;
      $('#date_id').val(_id);
      $('#date_date').val(_date);
      $('#date_fee').val(_fee);
    });
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