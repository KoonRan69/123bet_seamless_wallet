@extends('System.Layouts.Master')
@section('title')
Up Document
@endsection
@section('css')
<!--THIS PAGE LEVEL CSS-->
<meta name="_token" content="{!! csrf_token() !!}" />
<link href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
      rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css" rel="stylesheet" />
<link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
<style>
  .dtp-btn-cancel {
    background: #9E9E9E;
  }

  .dtp-btn-ok {
    background: #009688;
  }
  td{
    vertical-align: middle!important;
  }
  .table-hover>tbody>tr:hover {
    background-color: #f5f5f51f;
  }
  .switch input {
    width: 0;
    height: 0;
    border: none;
  }
  .switch label {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    position: relative;
    padding-left: 2.5em;
    height: 1em;
    display: -webkit-inline-box;
    display: inline-flex;
    -webkit-box-align: center;
    align-items: center;
    cursor: pointer;
  }
  .switch label:before, .switch label:after {
    content: "";
    display: inline-block;
    position: absolute;
    -webkit-transition-duration: 0.2s;
    transition-duration: 0.2s;
  }
  .switch label:before {
    left: 0;
    width: 2em;
    height: 1em;
    background-color: lightgray;
    border-radius: 1em;
  }
  .switch label:after {
    left: 0.15em;
    width: 0.7em;
    height: 0.7em;
    background-color: white;
    border-radius: 50%;
  }
  .switch input[type="checkbox"]:checked + label:before {
    background-color: red;
  }
  .switch input[type="checkbox"]:checked + label:after {
    left: 1.15em;
  }
</style>
@endsection
@section('content')
<div class="content">
  <div class="row">
    <div class="col-sm-12">
      <div class="page-header-title">
        <h4 class="pull-left page-title">Document</h4>
        <ol class="breadcrumb pull-right">
          <li><a href="javascript:void(0);">EGGSBOOK</a></li>
          <li class="active" style="color:#fff">Document</li>
        </ol>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default card-view">
          <div class="panel-heading bg-success">
            <h4 class="panel-title text-left">Up Documents</h4>
          </div>
          <div class="panel-body panel-wrapper">
            <form method="post" action="{{ route('admin.postFileDoc') }}" enctype="multipart/form-data">
              @csrf
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <!-- form -->
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="">File <span class="text-danger">Nhớ nén trước khi up</span></label>
                          <input type="file" name="file_doc" class="form-control" placeholder="Enter image">
                        </div>
                      </div>			                                
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="">Title</label>
                          <input id="title" type="text" name="title" class="form-control" placeholder="Enter title file">
                        </div>
                      </div>                            
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="">Parent directory</label>
                          <select class="form-control"
                                  name="parent">
                            <option selected value=""
                                    {{request()->input('status') == '' ? 'selected' : ''}}>
                              --- Select ---</option>
                            @foreach($listParent as $l)
                            <option value="{{$l->Doc_ID}}"
                                    {{request()->input('status') ==  $l->Doc_ID ? 'selected' : ''}}>
                              {{$l->Doc_Title}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 text-left">
                        <button type="submit" class="btn btn-success info">
                          <i class="fa fa-save" aria-hidden="true"></i>
                          Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="panel panel-default card-view">
          <div class="panel-heading">
            <h4 class="panel-title text-left">List Notification</h4>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th data-toggle="true">
                      #
                    </th>
                    <th>
                      File
                    </th>
                    <th>
                      Title
                    </th>
                    <th>
                      Parent directory
                    </th>
                    <th>
                      Status
                    </th>
                    <th>
                      Action
                    </th>
                  </tr>
                </thead>
                <tbody> 
                  @foreach($list as $noti)                           
                  <tr>
                    <td>{{$noti->Doc_ID}}</td>
                    <td>
                      @if($noti->Doc_File != '')
                      <span class="badge badge-success">
                        <a href="{{$noti->Doc_File}}" class="text-white" target="_blank">Link file</a></span>
                      @else
                      <span class="badge badge-danger">
                        Không có file</span>
                      @endif
                    </td>
                    <td>
                      <span>{{$noti->Doc_Title}}</span> 
                    </td>

                    <td>
                      @if($noti->Doc_ParentID != null)
                      <span class="badge badge-success">{{$listParentList[$noti->Doc_ParentID]}}</span> 
                      @else
                      <span class="badge badge-warning">No Parent</span>
                      @endif
                    </td>
                    <td>
                      <span class="badge badge-{{$noti->Doc_Status == 1 ? 'success' : 'danger'}}">
                        {{$noti->Doc_Status == 1 ? 'Used' : 'no use'}}</span>
                    </td>

                    <td>
                      <!--
@if($noti->Doc_Status == 1)
<a type="button" href="{{ route('admin.getHideFile', $noti->Doc_ID) }}"
class="btnDelete btn btn-rounded btn-noborder btn-warning min-width-125 mt-2">
Use
</a> 
@else($noti->Doc_Status == 0)
<a type="button" href="{{ route('admin.getHideFile', $noti->Doc_ID) }}"
class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2">
No use
</a> 
@endif
-->
                      <a type="button" href="{{ route('admin.getDeleteFile', $noti->Doc_ID) }}"
                         class="btnDelete btn btn-rounded btn-noborder btn-danger min-width-125 mt-2">
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
  $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
@endsection