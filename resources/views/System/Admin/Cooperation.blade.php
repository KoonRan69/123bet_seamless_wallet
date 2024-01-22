@extends('System.Layouts.Master')
@section('title')
Cooperation Contact
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
                    <li><a href="javascript:void(0);">DAPP</a></li>
                    <li class="active" style="color:#fff">Member</li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
		<div class="row">
		    <div class="col-xl-12">
		        <div class="panel panel-default card-view">
		            <div class="panel-heading">
			            <h4 class="panel-title text-left">List </h4>
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
		                                    Email
		                                </th>
		                                <th>
		                                    Name
		                                </th>
		                                <th>
		                                    Project name
		                                </th>
		                                <th>
		                                    Project website
		                                </th>
		                                <th>
		                                    With link
		                                </th>
		                                <th>
		                                    Contract
		                                </th>
		                                <th>
		                                    Amount
		                                </th>
		                                <th>
		                                    Orther infomation
		                                </th>
		                                <th>
		                                    Stauts
		                                </th>
		                                <th>
		                                    Date time
		                                </th>
		                            </tr>
		                        </thead>
		                        <tbody> 
                                  	@php
                                  	$arrWith = [1=> 'Swap Pancakeswap', 2=> 'Reciprocal USDT'];
                                  	@endphp
				         			@foreach($list as $l)                           
			                        <tr>
		                            	<td>{{$l->id}}</td>
		                            	<td>{{$l->email}}</td>
		                            	<td>{{$l->name}}</td>
		                            	<td>{{$l->project_name}}</td>
		                            	<td>{{$l->project_website}}</td>
		                            	<td>{{$arrWith[$l->with_link]}}</td>
		                            	<td>{{$l->contact}}</td>
		                            	<td>{{$l->amount}}</td>
		                            	<td>{{$l->other_information}}</td>
		                            	<td>{{$l->status}}</td>
		                            	<td>{{$l->created_at}}</td>
		                            	
		                            </tr>
		                            @endforeach
		                        </tbody>
		                    </table>
                            <div class="row">
                              {{ $list->links() }}
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
</script>
@endsection