@extends('System.Layouts.Master')
@section('title')
Up Notification
@endsection
@section('css')
<meta name="_token" content="{!! csrf_token() !!}" />

<!--THIS PAGE LEVEL CSS-->
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
                <h4 class="pull-left page-title">Member</h4>
                <ol class="breadcrumb pull-right">
                    <li><a href="javascript:void(0);">DAPP</a></li>
                    <li class="active" style="color:#fff">Member</li>
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
			            <h4 class="panel-title text-left">Up Notification</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <form method="post" action="{{ route('admin.postNoti') }}" enctype="multipart/form-data">
			                @csrf
		                    <div class="panel-body">
		                        <div class="row">
		                            <div class="col-md-12">
			                            <div class="row">
		                                <!-- form -->
			                                <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Image</label>
													<input type="file" name="notification_image" class="form-control" placeholder="Enter image">
												</div>
			                                </div>
			                                <div class="col-md-3 col-lg-3">
				                                <div class="form-group">
					                                <label for="">Location</label>
					                                <p>
														<span class="switch">
															<input type="checkbox" id="landing" value="1" name="landing">
															<label for="landing">Landing</label>
														</span>
													</p>
				                                </div>
			                                </div>
			                                <div class="col-md-3 col-lg-3">
				                                <div class="form-group">
					                                <label for="">Location</label>
					                                <p>
														<span class="switch">
															<input type="checkbox" id="system" value="1" name="system">
															<label for="system">System</label>
														</span>
													</p>
				                                </div>
			                                </div>
                                           <div class="col-md-3 col-lg-3">
				                                <div class="form-group">
					                                <label for="">Location</label>
					                                <p>
														<span class="switch">
															<input type="checkbox" id="exchange" value="1" name="exchange">
															<label for="exchange">Exchange</label>
														</span>
													</p>
				                                </div>
			                                </div>
			                                <div class="col-md-3 col-lg-3">
				                                <div class="form-group">
					                                <label for="">Promotion</label>
					                                <p>
														<span class="switch">
															<input type="checkbox" id="promotion" value="1" name="promotion">
															<label for="promotion">Promotion</label>
														</span>
													</p>
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
		                                    Image
		                                </th>
		                                <th>
		                                    Landing
		                                </th>
		                                <th>
		                                    System
		                                </th>
		                                <th>
		                                    Promotion
		                                </th>
		                                <th>
		                                    Action
		                                </th>
		                            </tr>
		                        </thead>
		                        <tbody> 
				         			@foreach($notiImage as $noti)                           
			                        <tr>
		                            	<td>{{$noti->id}}</td>
		                            	<td>
			                            	<img src="{{$noti->image}}" width="70">
		                            	</td>
		                            	<td>
			                            	<span class="badge badge-{{$noti->landing == 1 ? 'success' : 'danger'}}">{{$noti->landing == 1 ? 'yes' : 'no'}}</span> 
		                            	</td>
		                            	<td>
			                            	<span class="badge badge-{{$noti->system == 1 ? 'success' : 'danger'}}">{{$noti->system == 1 ? 'yes' : 'no'}}</span> 
		                            	</td>
		                            	<td>
			                            	<span class="badge badge-{{$noti->promotion == 1 ? 'success' : 'danger'}}">{{$noti->promotion == 1 ? 'yes' : 'no'}}</span> 
		                            	</td>
		                            	<td>
		                            		@if($noti->status == 1)
			                            		<a type="button" href="{{ route('admin.getHideNoti', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-warning min-width-125 mt-2">
			                                        	Hide notification
												</a> 
		                                    @else($noti->status == 0)
		                                    	<a type="button" href="{{ route('admin.getHideNoti', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2">
													Turn on notification
												</a> 
											@endif
											<a type="button" href="{{ route('admin.getDeleteNoti', $noti->id) }}"
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