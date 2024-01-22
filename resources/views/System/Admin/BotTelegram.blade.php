@extends('System.Layouts.Master')
@section('title')
Setting bot
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" />

<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!-- Summernote css -->
{{-- <link href="assets/plugins/summernote/summernote.css" rel="stylesheet" /> --}}
<!--bootstrap-wysihtml5-->

<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css">
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
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
                <h4 class="pull-left page-title">Member</h4>
                <ol class="breadcrumb pull-right">
                    <li><a href="javascript:void(0);">123betnow</a></li>
                    <li class="active" style="color:#fff">setting bot</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
		<div class="row">
		    <div class="col-md-6 col-lg-8">
		        <div class="panel panel-default card-view">
		            <div class="panel-heading">
			            <h4 class="panel-title text-left">Add message bot</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <!--<form method="post" action="{{route('system.bot.postBotTelegram')}}" enctype="multipart/form-data">-->
		                <form id="formSendMessageBot" enctype="multipart/form-data" >
			                @csrf
		                    <div class="panel-body">
		                        <div class="row">
		                            <div class="col-md-12">
			                            <div class="row">
		                                <!-- form -->
			                                <div class="col-md-12 col-lg-12">
				                                <div class="form-group">
													<label for="">Image (Dimensions: 800x450 px) </label>
													<input type="file" name="image" id="img_bot_telegram" class="dropify bg-dark" />
												</div>
			                                </div>
			                                <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Title</label>
					                                <input id="title_bot_telegram" type="name" name="title" class="form-control" placeholder="Enter User ID">
				                                </div>
			                                </div>
			                                <div class="col-md-12">
				                                <div class="form-group">
			                                    	<label for="">Description</label>
                                                  <textarea id="description_bot_telegram" type="text" rows="6" name="description" class="form-control" placeholder="Enter description..."></textarea>
				                                </div>
			                                </div>
			                                <div class="col-md-12 text-right">
				                                <button type="button" id="send_message_tele" class="btn btn-primary info"><i class="fa fa-save" aria-hidden="true"></i>
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
		    <div class="col-md-6 col-lg-4">
		        <div class="panel panel-default card-view">
		            <div class="panel-heading">
			            <h4 class="panel-title text-left">Setting chanel</h4>
		            </div>
		            <div class="panel-body panel-wrapper">
		                <form method="POST" action="{{route('system.bot.addChanelBot')}}">
			                @csrf
		                    <div class="panel-body">
                                <div class="form-group">
                                  <label for="">Chanel name </label>
                                  <input type="text" name="name" value="{{$idChanel ? $idChanel->name : ''}}" class="form-control" />
                                </div>
                                <div class="form-group">
                                  <button type="" class="btn btn-primary info"><i class="fa fa-save" aria-hidden="true"></i>
                                    Save</button>
                                </div>
		
		                    </div>
		                </form>
		            </div>
		        </div>
		    </div>
		</div>
      {{--
		<div class="row">
		    <div class="col-xl-12">
		        <div class="panel panel-default card-view">
		            <div class="panel-heading">
			            <h4 class="panel-title text-left">List</h4>
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
		                                    Title
		                                </th>
		                                <th>
		                                    Action
		                                </th>
		                            </tr>
		                        </thead>
		                        <tbody> 
                                  {{--
				         			@foreach($listBlog as $blog)                           
			                        <tr>
		                            	<td>{{$blog->id}}</td>
		                            	<td>
			                            	<img src="https://media.eggsbook.com/{{$blog->banner}}" width="100">
		                            	</td>
		                            	<td>{{$blog->title}}</td>
		                            	
		                            	<td>
											<a type="button" href="{{ route('system.admin.getEditBlog', $blog->id) }}"
												class=" btn btn-rounded btn-noborder btn-warning min-width-125 mt-2">
												Edit
											</a> 
											<a type="button" href="{{ route('system.admin.getDeleteBlog', $blog->id) }}"
												class=" btn btn-rounded btn-noborder btn-danger min-width-125 mt-2">
												Delete
											</a> 
		                                </td>
		                            </tr>
		                            @endforeach
                                  --}}
		                        </tbody>
		                    </table>
                            <div class="row">
                            </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>--}}
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<script src="assets/js/summernote-bs4.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
<script src="../public/vendor/laravel-filemanager/js/lfm.js?v={{ time() }}"></script>
<script src="assets/js/form-summernote.init.js?v={{ time() }}"></script>
<script src="assets/js/app.js"></script>

<script>
  $(document).ready(function() {
		"use strict";
		
		/* Basic Init*/
		$('.dropify').dropify();

		/* Translated Init*/
		$('.dropify-fr').dropify({
			messages: {
				default: 'Glissez-dĂ©posez un fichier ici ou cliquez',
				replace: 'Glissez-dĂ©posez un fichier ou cliquez pour remplacer',
				remove:  'Supprimer',
				error:   'DĂ©solĂ©, le fichier trop volumineux'
			}
		});

		/* Used events */
		// 
		var drEvent = $('#input-file-events').dropify();

		drEvent.on('dropify.beforeClear', function(event, element){
			return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
		});

		drEvent.on('dropify.afterClear', function(event, element){
			alert('File deleted');
		});

		drEvent.on('dropify.errors', function(event, element){
			console.log('Has Errors');
		});

		var drDestroy = $('#input-file-to-destroy').dropify();
		drDestroy = drDestroy.data('dropify')
		$('#toggleDropify').on('click', function(e){
			e.preventDefault();
			if (drDestroy.isDropified()) {
				drDestroy.destroy();
			} else {
				drDestroy.init();
			}
		});

	});
</script>
<script>

  $(document).ready(function() {
    $( "#send_message_tele" ).click(function() {
		$("#formSendMessageBot").submit();
    });
	$('#formSendMessageBot').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
          	url: '{{route('system.bot.postBotTelegram')}}',
            headers: {
              'X-CSRF-TOKEN':'{{ csrf_token() }}',
              //'Content-Type':'application/json'
            },
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
          		if(data.status == 'error'){
                  	toastr.error(data.message, 'Error!', {timeOut: 3500});
                }else{
                	toastr.success(data.message, 'Success!', {timeOut: 3500});  
                }
                
                console.log(data);
            },
            error: function(data){
                toastr.error(data.message, 'Error!', {timeOut: 3500});
                console.log(data);
            }
        });
    }));
 
  });
</script>
@endsection