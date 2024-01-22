@extends('System.Layouts.Master')
@section('title')
Language translation
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
			            <h4 class="panel-title text-left">Create language translation</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <form method="post" action="{{ route('admin.postLanguageTranslation') }}" enctype="multipart/form-data">
			                @csrf
		                    <div class="panel-body">
								<input type="hidden" name="id" value="{{$lang->id ?? ''}}">
		                        <div class="row">
		                            <div class="col-md-12">
			                            <div class="row">
		                                <!-- form -->
											<div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">ID</label>
                                                    <input class="form-control" type="text" name="category_id" id="" placeholder="Enter Vietnam title" value="{{$lang->category_id ?? ''}}" disabled>
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Vietnam Title</label>
                                                    <input class="form-control" type="text" name="vi_title" id="" placeholder="Enter Vietnam title" value="{{$lang->vi_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">English Title</label>
                                                    <input class="form-control" type="text" name="en_title" id="" placeholder="Enter English title" value="{{$lang->en_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">China Title</label>
                                                    <input class="form-control" type="text" name="cn_title" id="" placeholder="Enter China title" value="{{$lang->cn_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Korea Title</label>
                                                    <input class="form-control" type="text" name="kr_title" id="" placeholder="Enter Korea title" value="{{$lang->kr_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Russia Title</label>
                                                    <input class="form-control" type="text" name="ru_title" id="" placeholder="Enter Russia title" value="{{$lang->ru_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Spain Title</label>
                                                    <input class="form-control" type="text" name="es_title" id="" placeholder="Enter Spain title" value="{{$lang->es_title ?? ''}}">
												</div>
			                                </div>
											<div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Japan Title</label>
                                                    <input class="form-control" type="text" name="ja_title" id="" placeholder="Enter Spain title" value="{{$lang->ja_title ?? ''}}">
												</div>
			                                </div>
                                            <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Category</label>
                                                    <input class="form-control" type="text" name="category" id="" placeholder="Enter Category" value="{{$lang->category ?? ''}}">
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

						<form action="{{route('admin.changeVersionLanguage')}}" method="post">
							@csrf
							<div class="panel-body">
								<div class="row">
										<h4 style="
											color: #333333;
											padding-left: 10px;
										">Change version language</h4>
										@foreach($lang_version as $key => $value)
										<div class="col-md-2">
											<div class="form-group">
												<label for="">{{$value->language}}</label>
												<input class="form-control" type="text" name="{{$value->key}}" id="" placeholder="Enter Version" value="{{$value->version ?? ''}}">
											</div>
										</div>
										@endforeach
										<div class="col-md-12 text-left">
											<button type="submit" class="btn btn-success info">
												<i class="fa fa-save" aria-hidden="true"></i>
											Save</button>
										</div>
								</div>
							</div>
						</form>

						<form action="{{ route('admin.getLanguageTranslation') }}" method="get">
							@csrf
							<div class="panel-body">
								<div class="row">
										<h4 style="
											color: #333333;
											padding-left: 10px;
										">Search</h4>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Vietnam title</label>
												<input class="form-control" type="text" name="vi_title" id="" placeholder="Enter title" value="">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">English title</label>
												<input class="form-control" type="text" name="en_title" id="" placeholder="Enter title" value="">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="">Category</label>
												<input class="form-control" type="text" name="category" id="" placeholder="Enter category" value="">
											</div>
										</div>
										<div class="col-md-12 text-left">
											<button type="submit" class="btn btn-success info">
												<i class="fa fa-search" aria-hidden="true"></i>
											Search</button>
											<a href="{{route('admin.getLanguageTranslation')}}"><button class="btn btn-danger danger">
												<i class="fa fa-cancel" aria-hidden="true"></i>
											Cancel</button></a>
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
			            <h4 class="panel-title text-left">List language</h4>
		            </div>
		            <div class="panel-body">
		                <div class="table-responsive">
		                    <table class="table table-hover">
		                        <thead>
		                            <tr>
		                                <th>ID</th>
		                                <th>VietNam Title</th>
		                                <th>English Title</th>
		                                <th>Chinese Title</th>
		                                <th>Korean Title</th>
		                                <th>Russian Title</th>
                                        <th>Spain Title</th>
                                        <th>Japan Title</th>
                                        <th>Category</th>
										<th>Status</th>
										<th>Function</th>
		                            </tr>
		                        </thead>
		                        <tbody> 
									<tr>
                                        <th style="color: #333333">Version</th>
										@foreach($lang_version as $key => $value)
		                                <th style="color: #333333">{{$value->version}}</th>
		                                @endforeach
										<th style="color: #333333"></th>
									</tr>
				         			@foreach($multiLang as $key => $lang)
                                     <tr>
		                                <th style="color: #333333">{{$lang->category_id}}</th>
		                                <th style="color: #333333">{{$lang->vi_title}}</th>
		                                <th style="color: #333333">{{$lang->en_title}}</th>
		                                <th style="color: #333333">{{$lang->cn_title}}</th>
		                                <th style="color: #333333">{{$lang->kr_title}}</th>
		                                <th style="color: #333333">{{$lang->ru_title}}</th>
                                        <th style="color: #333333">{{$lang->es_title}}</th>
                                        <th style="color: #333333">{{$lang->ja_title}}</th>
                                        <th style="color: #333333">{{$lang->category}}</th>
										<th style="color: #333333">{{$lang->status}}</th>
										<th style="color: #333333">
										<a type="button"
                                         href="{{route('admin.editLanguageTranslation', $lang->id)}}"
                                         class="btnDelete btn btn-rounded btn-noborder btn-success min-width-125 mt-2"
                                         >Edit</a>
                                        <!-- <a type="button"
                                         href="{{route('admin.deleteLanguageTranslation', $lang->id)}}"
                                         class="btnDelete btn btn-rounded btn-noborder btn-danger min-width-125 mt-2"
                                         onclick="return confirm('Are you sure to delete?')"
                                         >Delete</a> -->
                                         </th>
		                            </tr>
                                    @endforeach
		                        </tbody>
								{{$multiLang->appends(request()->input())->links('System.Layouts.Pagination')}}
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