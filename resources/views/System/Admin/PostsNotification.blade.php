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
<link href="assets/plugins/summernote/summernote.css" rel="stylesheet" />
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
			            <h4 class="panel-title text-left">Up Posts Notification</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <form method="post" action="{{ route('admin.postNotiPost') }}" enctype="multipart/form-data">
			                @csrf
		                    <div class="panel-body">
		                        <div class="row">
		                            <div class="col-md-12">
			                            <div class="row">
		                                <!-- form -->
                                            <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">En Tittle</label>
                                                    <input  class="form-control" type="text" name="noti_title_en" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">En Content</label>
                                                    <textarea class="form-control" name="noti_content_en" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                          	{{--
			                                <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">Vi Tittle</label>
                                                    <input class="form-control" type="text" name="noti_title_vn" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">Vi Content</label>
                                                    <textarea class="form-control" name="noti_content_vn" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                            <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">Cn Tittle</label>
                                                    <input class="form-control" type="text" name="noti_title_cn" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">Cn Content</label>
                                                    <textarea class="form-control" name="noti_content_cn" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                            <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">Kr Tittle</label>
                                                    <input class="form-control" type="text" name="noti_title_kr" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">Kr Content</label>
                                                    <textarea class="form-control" name="noti_content_kr" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                            <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">Ru Tittle</label>
                                                    <input class="form-control" type="text" name="noti_title_ru" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">Ru Content</label>
                                                    <textarea class="form-control" name="noti_content_ru" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                            <div class="col-md-4">
				                                <div class="form-group">
			                                    	<label for="">Es Tittle</label>
                                                    <input class="form-control" type="text" name="noti_title_es" id="">
												</div>
                                                <div class="form-group">
			                                    	<label for="">Es Content</label>
                                                    <textarea  class="form-control" name="noti_content_es" id="" cols="30" rows="10"></textarea>
												</div>
			                                </div>
                                          	--}}
											<div class="col-xs-8 text-left">
				                                <div class="form-group">
			                                    	<label for="">Order</label>
                                                    <input class="form-control" type="number" name="order" id="" value="1">
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
			            <h4 class="panel-title text-left">List Posts Notification </h4>
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
		                                    VietNam Title
		                                </th>
                                        <th>
		                                    VietNam Content
		                                </th>
		                                <th>
		                                    English Title
		                                </th>
                                        <th>
                                            English Content
		                                </th>
                                        
		                                <th>
		                                    Chinese Title
		                                </th>
                                        <th>
                                            Chinese Content
		                                </th>
		                                <th>
		                                    Korean Title
		                                </th>
                                        <th>
                                            Korean Content
		                                </th>
		                                <th>
		                                    Russian Title
		                                </th>
                                        <th>
                                            Russian Content
		                                </th>
                                        <th>
		                                    Spain Title
		                                </th>
                                        <th>
                                            Spain Content
		                                </th>
										<th>
                                            Order
		                                </th>
										<th>
                                            Action
		                                </th>
                                        
		                            </tr>
		                        </thead>
		                        <tbody> 
				         			@foreach($notiPost as $noti)                           
			                        <tr>
		                            	<td>{{$noti->id}}</td>
		                            	<td>
			                            	{!!$noti->vi_title!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->vi_content!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->en_title!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->en_content!!}
		                            	</td>
                                        <td>
			                            	{!! $noti->cn_title	!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->cn_content!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->kr_title!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->kr_content!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->ru_title!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->ru_content!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->es_title!!}
		                            	</td>
                                        <td>
			                            	{!!$noti->es_content!!}
		                            	</td>
										<td>
			                            	{!!$noti->order!!}
		                            	</td>
										<td>
											@if($noti->status == 1)
			                            		<a type="button" href="{{ route('admin.getHideNotiPosts', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-warning min-width-125 mt-2">
			                                        	Hide
												</a> 
		                                    @else($noti->status == 0)
		                                    	<a type="button" href="{{ route('admin.getHideNotiPosts', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2">
													Show
												</a> 
											@endif
											@if($noti->is_new == 1)
			                            		<a type="button" href="{{ route('admin.getSetNew', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-warning min-width-125 mt-2">
			                                        	Unset New
												</a> 
		                                    @else($noti->is_new == 0)
		                                    	<a type="button" href="{{ route('admin.getSetNew', $noti->id) }}"
		                                        	class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2">
													Set New
												</a> 
											@endif
											<a type="button" href="{{ route('admin.getDeleteNotiPosts', $noti->id) }}"
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

<script src="assets/js/summernote-bs4.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
<script src="assets/js/form-summernote.init.js?v={{ time() }}"></script>
<script>
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
    $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
@endsection