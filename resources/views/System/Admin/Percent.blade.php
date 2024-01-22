@extends('System.Layouts.Master')
@section('title', 'Admin-Wallet')
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
<link href="datetime/assets/css/style.css" rel="stylesheet">
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
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
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Percent Profit</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Percent Profit</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-sm-3 col-lg-2">
                    <div class="panel panel-default card-view p-y">
                        <div class="nav-active-border left b-primary">
                            <ul class="nav nav-sm flex-column">
                                @for($i = 1;$i <= count($percent); $i++)
                                <li class="nav-item">
                                    <a class="nav-link block {{$i == 1 ? 'active' : ''}} text-bold f-20 m-b" style="color:#777!important;"  href data-toggle="tab" data-target="#tab-{{$i}}">${{$arrMinMax[$i]['min']}} - ${{$arrMinMax[$i]['max']}}</a>
                                </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 col-lg-10">
                    <div class="panel panel-default card-view tab-content pos-rlt">
                        
                        @for($i = 1;$i <= count($percent); $i++)
                        <div class="tab-pane {{$i == 1 ? 'active' : ''}} m-t" id="tab-{{$i}}">
                            <div class="box hover-box-shadow">
                                <div class="table-responsive">
                                    <table class="table table-hover b-t m-t">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%">Date</th>
                                                <th style="width: 20%">Percent</th>
                                                <th style="width: 20%">Min</th>
                                                <th style="width: 20%">Max</th>
                                                <th style="width: 20%">Edit %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($percent[$i] as $upt1)
                                            <tr>
                                                <td>{{$upt1->Percent_Time}}</td>
                                                <td>{{$upt1->Percent_Percent * 100}} %</td>
                                                <td>{{$upt1->package_Min*1}}</td>
                                                <td>{{$upt1->package_Max*1}}</td>
                                                <td width="10%">
                                                <form action="{{ route('system.admin.postChangePercent') }}" method="post">@csrf
                                                    <input type="number" step="any" name="Percent" placeholder="%" class="form-control" value="">
                                                    <input type="hidden" name="ID" value="{{$upt1->Percent_ID}}"><button class="btn btn-rounded btn-primary ">Edit</button>
                                                </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endfor

                    </div>
                </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection