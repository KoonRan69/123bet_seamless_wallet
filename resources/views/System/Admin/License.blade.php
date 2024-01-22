@extends('System.Layouts.Master')
@section('title', 'Admin License')
@section('css')
<meta name="_token" content="{!! csrf_token() !!}" />
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
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
          <h4 class="pull-left page-title">License</h4>
          <ol class="breadcrumb pull-right">
            <li><a href="javascript:void(0);">DAPP</a></li>
            <li class="active" style="color:#fff">License</li>
          </ol>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <!-- /Title -->
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading">
                  <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                    List License
                  </h6>
                </div>
                <div class="panel-wrapper collapse in">
                  <div class="panel-body">
                    <div class="table-wrap">
                      <div class="table-responsive">
                        {{ $getLicense->appends(request()->input())->links('System.Layouts.Pagination') }}
                        <div style="clear:both"></div>
                        <table id="myTable1"
                               class="dt-responsive table table-striped table-bordered table-responsive">
                          <thead>
                            <tr>
                              <th data-toggle="true">
                                ID
                              </th>
                              <th>
                                NAME
                              </th>
                              <th>
                                EMAIL
                              </th>
                              <th data-hide="phone">
                                WEBSITE
                              </th>
                              <th data-hide="phone,tablet">
                                PLAYER ID
                              </th>
                              <th data-hide="phone,tablet">
                                DISPUTID AMOUNT	
                              </th>
                              <th data-hide="phone,tablet">
                                CURRENCY
                              </th>
                              <th data-hide="phone,tablet">
                                TYPE
                              </th>
                              <th data-hide="phone,tablet">
                                STATUS
                              </th>
                              <th>CREATED AT</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($getLicense as $t)
                            <tr>
                              <td>{{ $t->id }}</td>
                              <td>{{ $t->name}}</td>
                              <td>{{ $t->email }}</td>
                              <td>{{ $t->website }}</td>
                              <td>{{ $t->playerid }}</td>
                              <td>{{ $t->disputed_amount }}</td>
                              <td>{{ $t->Currency_Name }}</td>
                              <td>
                                @if($t->type == 1)
                                <label class="bagde bagde-success">Complaints</label>
                                @else
                                <label class="bagde bagde-success">Self-Exclusion</label>
                                @endif
                              </td>
                              <td>
                                @if($t->status == 1)
                                <span class="badge badge-success">Replied</span>
                                @else
                                <span class="badge badge-warning">Waitting</span>
                                @endif
                                <a href="{{ route('system.admin.getDetailLicense', $t->id) }}"
                                   class="btn btn-primary btn-rounded">Detail</a>
                              </td>
                              <td>{{ $t->created_at }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        {{ $getLicense->appends(request()->input())->links('System.Layouts.Pagination') }}
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
@endsection
