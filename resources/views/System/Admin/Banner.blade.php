@extends('System.Layouts.Master')
@section('title', 'VoucherList')
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" type="text/css" />
<style>
  a:hover {
    cursor: pointer;
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

  .btn-filler {
    margin-bottom: 10px;
  }

  .pagination {
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
          <h4 class="pull-left page-title">Banner</h4>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <!-- Search -->
          <div class="row">
            <div class="col-md-12">
              <form method="POST" action="{{ route('system.admin.getBanner') }}" enctype="multipart/form-data">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="form-group">
                                <label for="input-1">Hình ảnh</label>
                                 <input name="banner_img" type="file" class="dropify"  data-height="100"> 
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-light px-5" value="Thêm">
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

          <!-- !Search-->
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">

              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div>
                    <h3 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                      Banner Table</h3>
                  </div>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        <div style="clear:both"></div>
                        <table id="member-list"
                               class=" dt-responsive table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th scope="col">ID</th>
                                <th scope="col">Hình ảnh</th>
                              <th scope="col">Chức năng</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($list as $item)
                                <tr>
                                    <td>{{ $item->banner_id }}</td>
                                    <td>
                                        <img style="width:100px;" id="image" src="{{ $item->banner_img }}" alt="image" />

                                    </td>
                                    <td>
                                      <form action="{{ route('system.admin.getBanner') }}" method="GET"enctype="multipart/form-data">
                            			@csrf
                                        <button class="btn btn-warning btn_edit" type="submit" data-id="{{ $item->banner_id }}" name="delete" value="{{ $item->banner_id }}">Xóa</button>
                                      </form>
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
          <!--Modal show profile -->

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

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>
<script>
  var today = new Date();
  var currentTime = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

  $('#dt-statistical').DataTable({
    "bLengthChange": false,
    "searching": false,
    "paging": false
  });
</script>

<script src="assets/jquery-table2excel/dist/jquery.table2excel.min.js"></script>

<script>
  $(function() {
    $('#exportTest').click(function(){
      $(".demo-foo-col-exp").table2excel({
        exclude: ".noExl",
        name: "Fishs",
        filename: "Fishs" + new Date().toISOString().replace(/[\-\:\.]/g, "")+".xls",
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
      });

    });
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
  $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });

  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });
</script>
	<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $('.dropify').dropify();
    </script>
@endsection