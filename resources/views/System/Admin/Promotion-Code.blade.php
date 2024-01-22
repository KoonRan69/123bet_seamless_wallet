@extends('System.Layouts.Master')
@section('title', 'Promotion Code')
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
          <h4 class="pull-left page-title">Promotion</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">DAPP</a></li>
            <li class="active" style="color:#fff">Code</li>
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
            <div class="col-md-6">
              <form method="GET" action="{{route('system.admin.getPromotionCode')}}">
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1">Code</label>
                                <input class="form-control" type="text"
                                       placeholder="Code"
                                       value="{{request()->input('code')}}"
                                       name="code">
                              </div>
                              <div class="form-group">
                                <div class="form-actions ">
                                  <button type="submit" class="btn-filler btn btn-lg1 btn-primary waves-effect"><i class="fa fa-search" aria-hidden="true"></i>
                                    Search
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
              <form method="POST" action="{{route('system.admin.createCodePromotion')}}"  enctype="multipart/form-data">
                @csrf
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label class="control-label mb-10" for="exampleInputpwd_1">Quantity</label>
                                <input class="form-control" type="number" placeholder="Quantity" value="{{request()->input('quantity')}}" name="quantity">
                              </div>
                              <div class="form-group">
                                <label class="control-label mb-10" for="exampleInputpwd_1">Price (EUSD)</label>
                                <input class="form-control" type="number" placeholder="Price" value="{{request()->input('price')}}" name="price">
                              </div>
                              <div class="form-group">
                                <label class="control-label mb-10" for="exampleInputpwd_1">Number of days of existence</label>
                                <input class="form-control" type="number" placeholder="Number of days of existence" value="{{request()->input('count_day')}}" name="count_day">
                              </div>
                              <div class="form-group"><label class="control-label mb-10" for="exampleInputpwd_1"><i class="fa fa-user" aria-hidden="true"></i> Description</label>
                                <input type="text" name="description" class="form-control"  placeholder="Enter description" value="{{request()->input('description')}}">
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="form-actions ">
                                <button type="submit" class="btn-filler btn btn-lg1 btn-primary waves-effect"><i class="fa fa-search" aria-hidden="true"></i>
                                  Create code
                                </button>
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
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div class="">
                    <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                      List Code</h6>
                  </div>
                </div>

                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        {{$listCode->appends(request()->input())->links('System.Layouts.Pagination')}}
                        <div style="clear:both"></div>
                        <table id="member-list-table"
                               class=" dt-responsive table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                ID
                              </th>
                              <th>
                                CODE
                              </th>
                              <th >
                                PRICE
                              </th>
                              <th>
                                QUANTITY
                              </th>
                              <th data-hide="phone">
                                EXPIRATION DATE
                              </th>
                              <th data-hide="phone">
                                DESCRIPTION
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($listCode as $v)
                            <tr>
                              <td><p>{{ $v->id }}</p></td>
                              <td>{{ $v->code }}</td>
                              <td>{{ number_format($v->price_bonus) }}</td>
                              <td>{{ $v->quantity }}</td>
                              <td>{{ $v->expiration_date }}</td>
                              <td>{{ $v->description }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {{$listCode->appends(request()->input())->links('System.Layouts.Pagination')}}
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
  $(document).ready(function() {
    $('.bt-loginID').click(function(e) {
      if ($('.bt-loginID').hasClass("disabled")) {
        event.preventDefault();
      }
      $('.bt-loginID').addClass("disabled");
    });
    var arr_email = [];
    $('#member-list-table').on('click', '.btn-edit-mail', function(){
      let id_user = $(this).data('id_user');
      var html_edit_mail = "<input id=\"input-mail-"+id_user+"\" type=\"text\" class=\"edit-email-input\" value=\""+$('#input-email-'+id_user).text()+"\">";
      arr_email[id_user] = $('#input-email-'+id_user).text();
      let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-disable-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>  <a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-save-mail btn btn-success btn-xs waves-effect waves-light\"><i class=\"fa fa-save\"> </i></a>"
      $('#action-email-'+id_user).html(html_action_mail);
      $('#input-email-'+id_user).html(html_edit_mail);
    });
    $('#member-list-table').on('click', '.btn-disable-mail', function(){
      let id_user = $(this).data('id_user');
      let html_edit_mail =  arr_email[id_user];
      let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-edit-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>"
      $('#action-email-'+id_user).html(html_action_mail);
      $('#input-email-'+id_user).html(html_edit_mail);
    });
    $('#member-list-table').on('click', '.btn-save-mail', function(){
      let id_user = $(this).data('id_user');
      let html_edit_mail = $('#input-mail-'+id_user).val();
      let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-edit-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>"
      $('#action-email-'+id_user).html(html_action_mail);
      $.ajax({
        url : '{{ route('system.admin.getEditMailByID') }}',
        type : "POST",
        dataType:"json",
        data : {
        _token: "{{ csrf_token() }}",
        id_user : id_user,
        new_email : html_edit_mail
      },
             success : function (result){
        if(!result){
          html_edit_mail = arr_email[id_user];
          toastr.error('Email Already Exists', 'Error!', {timeOut: 3500});
        }
        else{
          if(result == -1){
            html_edit_mail = arr_email[id_user];
            toastr.error('ID Does Not Exist', 'Error!', {timeOut: 3500});
          }
          else{
            html_edit_mail = $('#input-mail-'+id_user).val();
            toastr.success('Updated Email', 'Success!', {timeOut: 3500});
          }
        }
        $('#input-email-'+id_user).html(html_edit_mail);
      }
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
  $('#datetime').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
@endsection
