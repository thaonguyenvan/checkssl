@extends('admin.layout.index')
@section('head')
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Setting default</title>
    <base href="{{asset('')}}">

    <!-- Bootstrap -->
    <link href="public/admin_asset/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="public/admin_asset/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="public/admin_asset/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
   <link href="public/admin_asset/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="public/admin_asset/build/css/custom.min.css" rel="stylesheet">
</head>
@endsection

@section('content')
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Giới hạn mặc định</h4>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form action="admin/user/limit" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                      <label for="limit_ssl">Giới hạn SSL</label>
                      <input type="text" class="form-control" id="limit_ssl" value="{{$limit_default->limit_ssl}}" name="limit_ssl">
                    </div>
                    <div class="form-group">
                      <label for="limit_domain">Giới hạn Domain</label>
                      <input type="text" class="form-control" id="limit_domain" value="{{$limit_default->limit_domain}}" name="limit_domain">
                    </div>
                    <div class="form-group">
                      <label for="limit_email">Giới hạn Email</label>
                      <input type="text" class="form-control" id="limit_email" value="{{$limit_default->limit_email}}" name="limit_email">
                    </div>
                    <div class="form-group">
                      <label for="limit_tele">Giới hạn Telegram</label>
                      <input type="text" class="form-control" id="limit_tele" value="{{$limit_default->limit_tele}}" name="limit_tele">
                    </div>
                    <button type="submit" class="btn btn-primary">Thay đổi</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade bs-modal-noti" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel2">Cảnh báo mặc định</h4>
              </div>
              <div class="modal-body">
                <div class="container">
                  <form action="admin/user/noti" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="form-group">
                      <label for="send_noti_before">Cảnh báo trước khi hết hạn</label>
                      <input type="text" class="form-control" id="send_noti_before" value="{{$limit_default->send_noti_before}}" name="send_noti_before">
                    </div>
                    <div class="form-group">
                      <label for="send_noti_after">Cảnh báo lại sau</label>
                      <input type="text" class="form-control" id="send_noti_after" value="{{$limit_default->send_noti_after}}" name="send_noti_after">
                    </div>
                    <button type="submit" class="btn btn-primary">Thay đổi</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- page content -->
        <div class="right_col" role="main">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          @if (session('warning'))
          <div class="alert alert-warning">
            {{ session('warning') }}
          </div>
          @endif
          @if(count($errors) > 0)
          <div class="alert alert-danger">
            @foreach($errors->all() as $err)
            {{$err}} <br>
            @endforeach
          </div>
          @endif
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Giới hạn mặc định</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Giới hạn ssl</th>
                          <th>Giới hạn domain</th>
                          <th>Giới hạn email</th>
                          <th>Giới hạn telgram</th>
                          <th>Thao tác</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>{{$limit_default->limit_ssl}}</td>
                          <td>{{$limit_default->limit_domain}}</td>
                          <td>{{$limit_default->limit_email}}</td>
                          <td>{{$limit_default->limit_tele}}</td>
                          <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm">Sửa</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Số ngày cảnh báo mặc định</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table class="table">
                      <thead>
                        <tr>
                          <th>Gửi thông báo trước khi hết hạn</th>
                          <th>Gửi thông báo lại sau</th>
                          <th>Thao tác</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>{{$limit_default->send_noti_before}}</td>
                          <td>{{$limit_default->send_noti_after}}</td>
                          <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-noti">Sửa</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
@endsection

@section('script')
<!-- jQuery -->
    <script src="public/admin_asset/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="public/admin_asset/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="public/admin_asset/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="public/admin_asset/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="public/admin_asset/vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="public/admin_asset/build/js/custom.min.js"></script>
@endsection