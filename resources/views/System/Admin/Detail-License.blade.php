@extends('System.Layouts.Master')
@section('title', 'License detail')
@section('css')
<style>
  .media,
  .content-cmt {
    margin: 2%;
  }
  .btnstatus{
    margin: 5px 2%;
  }

  .img-cmt {
    border-radius: 50%;
    float: left;
    margin-right: 2%;
    background: #f5a32b;
    padding: 5px;
    border: 2px #f5a827 solid;
  }

  .content-cmt {
    background: linear-gradient(to top, #f5b61a, #f58345) !important;
    padding: 15px 25px;
    color: white;
    border-radius: 5px;
  }

  .info-user {
    background: #f3f3f3;
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 5px;
  }
  .info-user span{
    color: #0a8e88;
    font-weight: 600;
  }
  .info-user small{
    color: #0a8e88;
  }
  textarea {
    resize: none;
  }
</style>
@endsection
@section('content')
<div class="content">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="container-fluid">
          <!-- /Title -->
          <!-- Row -->
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default card-view">
                <div class="panel-heading" style="margin-bottom: 3em;">
                  <div class="row">
                    <div class="pull-left">
                      <h3 class="panel-title txt-light">LICENSE ID:{{$detail->id}} </h3>
                      @if($detail->type == 1)
                      <p>Complaints</p>
                      @else
                      <p>Self-Exclusion</p>
                      @endif

                    </div>
                  </div>
                </div>
                <div class="media mb-4 mt-1">
                  <img class="img-cmt d-flex mr-2 rounded-circle avatar-sm"
                       src="assets/images/users/userProfile.png" width="50px"
                       alt="Generic placeholder image">

                  <div class="media-body info-user">
                    <span class="float-right">{{ $detail->created_at }}</span>
                    <h6 class="m-0 font-14 text-info">From:
                      {{ $detail->email}} - Name {{$detail->name}}</h6>
                    @if($detail->playerid)
                    <small class="text-muted">ID: {{$detail->playerid}}</small>
                    @endif
                  </div>
                </div>
                <p class="content-cmt"><i class="fa fa-angle-right" aria-hidden="true"></i> {!! $detail->message !!}</p>
                <a href="{{ route('system.admin.getLicense') }}"
                   class="btn btn-success  btnstatus"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                @if($detail->status == 1)
                <span class="badge badge-success btnstatus">Replied</span>
                @else
                <a href="{{ route('system.admin.getStatusLicense', $detail->id) }}"
                   class="btn btn-warning btnstatus">Waitting</a>
                @endif
              </div>
            </div>
          </div>
        </div>
        <!-- end inbox-rightbar-->
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
@endsection