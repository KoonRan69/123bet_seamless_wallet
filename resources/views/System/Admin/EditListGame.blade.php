@extends('System.Layouts.Master')
@section('title')
Edit blog
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
<!-- Summernote css -->
<link href="assets/plugins/summernote/summernote.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet"
    type="text/css" />
<!--bootstrap-wysihtml5-->
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css">
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" type="text/css" />
<style>
    .dtp-btn-cancel {
        background: #9E9E9E;
    }
	.btn:not(.btn-link):not(.btn-circle){
		color: #fff;
	}
	.note-current-color-button{
		color: #000!important;
	}
    .dtp-btn-ok {
        background: #009688;
    }
	.table-hover>tbody>tr:hover {
    background-color: #f5f5f51f;
}
</style>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header-title">
                <h4 class="pull-left page-title">Edit List Game</h4>
                <ol class="breadcrumb pull-right">
                    <li><a href="javascript:void(0);">DAPP</a></li>
                    <li class="active" style="color:#fff">Edit List Game</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
		<div class="row">
		    <div class="col-md-12">
		        <div class="panel panel-default card-view">
		            <div class="panel-heading">
			            <h4 class="panel-title text-left">Edit List Game</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <form method="post" id="add_blog" action="{{ route('system.admin.editListGame', $getItem->id) }}" enctype="multipart/form-data">
			                @csrf
		                    <div class="panel-body">
		                        <div class="row">
		                            <div class="col-md-12">
			                            <div class="row">
                                        <!-- form -->
                                            <input id="id" type="hidden" value="{{$getItem->id}}"  name="id" class="form-control" placeholder="Enter User ID">
			                                <div class="col-md-12 col-lg-12">
				                                <div class="form-group">
                                                    <label for="">Image</label>
                                                    <input name="image" type="file" class="dropify" data-height="100"
                                            onchange="readURL(this);" data-default-file="{{$getItem->image }}">
												</div>
			                                </div>
                                          
                                          <div class="col-md-12 col-lg-12">
                                            <div class="form-group">
                                              <label for="">Icon Game</label>
                                              <input name="icon_game" type="file" class="dropify" data-height="100"
                                                     onchange="readURL(this);" data-default-file="{{$getItem->icon_game }}">
                                            </div>
                                          </div>
                                          
			                                <div class="col-md-6 col-lg-6">
				                                <div class="form-group">
			                                    	<label for="">Name</label>
					                                <input id="title" type="text" value="{{$getItem->name}}"  name="name" class="form-control" placeholder="Enter name">
				                                </div>
			                                </div>
			                                <div class="col-md-6 col-lg-6">
				                                <div class="form-group">
			                                    	<label for="">Display Name</label>
					                                <input id="description" type="name" value="{{$getItem->display_name}}" name="display_name" class="form-control" placeholder="Enter Display Name">
				                                </div>
			                                </div>
			                                <div class="col-md-12 text-right">
				                                <button type="submit" name="submit" class="btn btn-primary info"><i class="fa fa-save" aria-hidden="true"></i>
			                                        Save</button>
			                                </div>
			                            </div>
		                            </div>
		                        </div>
		                        <!-- end form -->
		
		                    </div>
		                </form>
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
<!-- Wysihtml js -->
{{-- <script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> --}}
<script type="text/javascript" src="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

{{-- <script src="assets/plugins/summernote/summernote.min.js"></script> --}}


<script src="assets/js/summernote-bs4.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
<script src="../public/vendor/laravel-filemanager/js/lfm.js?v={{ time() }}"></script>
<script src="assets/js/form-summernote.init.js?v={{ time() }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script src="assets/js/app.js"></script>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>
  $('.dropify').dropify();
</script>
@endsection