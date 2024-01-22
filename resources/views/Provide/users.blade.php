@extends('Provide.Layouts.Master')
@section('title', 'Admin-Game-123BetNow')

@section('content')
<div class="content">
  <div class="container">
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

    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 mx-auto">
              <form method="GET" action="">
                @csrf
                <input type="hidden" name="total" value="{{request()->input('total')}}">
                <div class="panel panel-default card-view">
                  <div class="panel-wrapper collapse in">
                    <div class="panel-body">
                      <div class="form-wrap">
                        <div class="form-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> User ID</label>
                                <input type="text" name="user_id" class="form-control"
                                       placeholder="Enter User ID"
                                       value="{{ request()->input('user_id') }}">
                              </div>
                            </div>
                            <!--/span-->

                            <div class="col-md-6">
                              <div class="form-group">
                                <label class="control-label mb-10"
                                       for="exampleInputpwd_1"><i class="fa fa-user"
                                                                  aria-hidden="true"></i> Email</label>
                                <input type="text" name="email" class="form-control"
                                       placeholder="Enter User ID"
                                       value="{{ request()->input('email') }}">
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                                <div class="form-actions mt-10">
                                  <button type="submit"
                                          class="btn-filler btn btn-lg1 btn-primary mr-10"><i
                                                                                              class="fa fa-search" aria-hidden="true"></i>
                                    Search
                                  </button>

                                  <a href="{{ route('provide.getUser') }}"
                                     class="btn-filler btn btn-default mr-10">Cancel</a>
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
          <!-- /Title -->
          <!--<div class="row">
<div class="col-md-12">
<form method="GET" action="">
<div class="panel panel-default card-view">
<div class="panel-wrapper collapse in">
<div class="panel-body">
<div class="form-wrap">
<div class="form-body">
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-user"
aria-hidden="true"></i> User ID</label>
<input class="form-control" type="text"
placeholder="User ID"
value="{{request()->input('UserID')}}"
name="UserID">
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-users"
aria-hidden="true"></i> Email</label>
<input class="form-control" type="text"
placeholder="Email"
value="{{request()->input('Email')}}" name="Email">
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-users"
aria-hidden="true"></i> Created Date</label>
<input type="text" class="form-control"
placeholder="Registration Time" name="datetime"
id="datetime"
value="{{request()->input('datetime')}}" />
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-users"
aria-hidden="true"></i> Sponsor</label>
<input class="form-control" type="text"
placeholder="Sponsor"
value="{{request()->input('sponsor')}}"
name="sponsor">
</div>
</div>


<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-users"
aria-hidden="true"></i> Tree</label>
<input class="form-control" type="text"
placeholder="Tree"
value="{{request()->input('tree')}}" name="tree">
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-users"
aria-hidden="true"></i> Status</label>
<select id="inputState" class="form-control"
name="status">
<option selected value=""
{{request()->input('status') == '' ? 'selected' : ''}}>
--- Select ---</option>
<option value="1"
{{request()->input('status') == '1' ? 'selected' : ''}}>
Active</option>
<option value="0"
{{request()->input('status') == '0' ? 'selected' : ''}}>
Not Active</option>
</select>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="control-label mb-10"><i class="fa fa-hand-o-down" aria-hidden="true"></i> User Level</label>
<select type="number" class="form-control" name="user_level">
<option value=""
{{request()->input('user_level') == '' ? 'selected' : ''}}>--Select--</option>
<option value="0"
{{request()->input('user_level') == '0' ? 'selected' : ''}}>Member</option>
<option value="1"
{{request()->input('user_level') == '1' ? 'selected' : ''}}>Admin</option>
<option value="2"
{{request()->input('user_level') == '2' ? 'selected' : ''}}>Finance</option>
<option value="3"
{{request()->input('user_level') == '3' ? 'selected' : ''}}>Support</option>
<option value="4"
{{request()->input('user_level') == '4' ? 'selected' : ''}}>Customer</option>
<option value="5"
{{request()->input('user_level') == '5' ? 'selected' : ''}}>Bot</option>
</select>
</div>
</div>
<div class="col-md-6 mb-10">
<div class="form-group">
<label class="control-label mb-10"
for="exampleInputpwd_1"><i class="fa fa-user"
aria-hidden="true"></i> Address register</label>
<input class="form-control" type="text"
placeholder="User ID"
value="{{request()->input('AddressRegister')}}"
name="AddressRegister">
</div>
</div>
<div class="col-md-6 mt-2">
<div class="form-group">
<div class="form-actions ">
{{--                                                        <button type="submit" class="btn btn-lg1 btn-success  mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>--}}
<button type="submit"
class="btn-filler btn btn-lg1 btn-primary waves-effect"><i
class="fa fa-search" aria-hidden="true"></i>
Search
</button>
<button type="submit"
class="btn-filler btn btn-success waves-effect"
style="" name="export" value="1"><i
class="fa fa-print" aria-hidden="true"></i>
Export</button>
<a href="{{ route('system.admin.getMemberListAdmin') }}"
class="btn-filler btn btn-default mr-10">Cancel</a>
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

</div> -->

          <!-- Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <div class="">
                    <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                      List User</h6>
                  </div>
                </div>

                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">

                        <table id="member-list-table"
                               class=" dt-responsive table table-striped table-bordered table-responsive"
                               cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                ID
                              </th>
                              <th>
                                MAIL
                              </th>
                              <th data-hide="phone">
                                USER AGIN
                              </th>
                              <th data-hide="phone">
                                USER VM555
                              </th>
                              <th data-hide="phone">
                                USER EVOL
                              </th>
                               <th data-hide="phone">
                                USER AWC
                              </th>

                            </tr>
                          </thead>
                          <tbody>
                            @foreach($user_list as $v)
                            <tr>
                              <td><p>{{ $v->User_ID }}</p></td>
                              <td>{{$v->User_Email}}</td>  
                              <td>{{$v->User_Agin}}</td>
                              <td>{{$v->User_WM555}}</td>
                              <td>{{$v->User_Evo}}</td>
                              <td>{{$v->User_AWC}}</td>

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
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/modernizr.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>

<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<script src="assets/js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@endsection

