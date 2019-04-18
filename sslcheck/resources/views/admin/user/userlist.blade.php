@extends('admin.layout.index')

@section('head')
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>List User</title>
  <base href="{{asset('')}}">
  <!-- Bootstrap -->
  <link href="public/admin_asset/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="public/admin_asset/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="public/admin_asset/vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="public/admin_asset/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Datatables -->
  <link href="public/admin_asset/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <link href="public/admin_asset/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="public/admin_asset/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
  <link href="public/admin_asset/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
  <link href="public/admin_asset/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="public/admin_asset/build/css/custom.min.css" rel="stylesheet">
</head>
@endsection

@section('content')
@foreach($users as $user)
<div class="modal fade bs-example-modal-lg-{{$user->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Sửa user</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="admin/user/edit/{{$user->id}}" id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="text" id="first-name" name="email" value="{{$user->email}}" required="required" class="form-control col-md-7 col-xs-12">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Quyền <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select name="role" class="form-control">
                <option @if($user->role == 0) {{'selected'}} @endif value="0">Member</option>
                <option @if($user->role == 1) {{'selected'}} @endif value="1">Admin</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Mật khẩu</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password" class="form-control col-md-7 col-xs-12" type="password" name="password">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Nhập lại mật khẩu
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password_confirmation" class="form-control col-md-7 col-xs-12" type="password" name="password_confirmation">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Số SSL
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="limit_ssl" class="form-control col-md-7 col-xs-12" value="{{$user->limit_ssl}}" type="text" name="limit_ssl">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Số Domain
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="limit_domain" class="form-control col-md-7 col-xs-12" value="{{$user->limit_domain}}" type="text" name="limit_domain">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Số Email
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="limit_email" class="form-control col-md-7 col-xs-12" value="{{$user->limit_email}}" type="text" name="limit_email">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Số Telegram
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="limit_tele" class="form-control col-md-7 col-xs-12" value="{{$user->limit_tele}}" type="text" name="limit_tele">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endforeach
<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>
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
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>List user</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p class="text-muted font-13 m-b-30">
            </p>
            <table id="datatable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Email</th>
                  <th>Verifired</th>
                  <th>Role</th>
                  <th>Email Noti</th>
                  <th>Tele Noti</th>
                  <th>Limit SSL</th>
                  <th>Limit Domain</th>
                  <th>Limit Email Noti</th>
                  <th>Limit Tele Noti</th>
                  <th colspan="2">Action</th>
                  <th colspan="2">SSL</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                <tr>
                  <td>{{$user->id}}</td>
                  <td>{{$user->email}}</td>
                  <td>
                    @if($user->verified == 1)
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-close"></i>
                    @endif
                  </td>
                  <td>
                    @if($user->role == 1)
                    {{"Admin"}}
                    @else
                    {{"Member"}}
                    @endif
                  </td>
                  <td>
                   <ul>
                    @foreach($emails as $email)
                    @if($user->id == $email->user_id)
                    <li>{{$email->email}}</li>
                    @endif
                    @endforeach
                  </ul>
                </td>
                <td>
                  <ul>
                    @foreach($teles as $tele)
                    @if($user->id == $tele->user_id)
                    <li>{{$tele->name}}</li>
                    @endif
                    @endforeach
                  </ul>
                </td>
                <td>{{$user->limit_ssl}}</td>
                <td>{{$user->limit_domain}}</td>
                <td>{{$user->limit_email}}</td>
                <td>{{$user->limit_tele}}</td>
                <td>
                  {{-- <a href="admin/user/edit/{{$user->id}}"><i class="fa fa-gear"></i> Sửa </a> --}}
                  <a data-toggle="modal" data-target=".bs-example-modal-lg-{{$user->id}}"><i class="fa fa-gear"></i> Sửa</a>
                  
                </td>
                <td>
                  <a href="admin/user/delete/{{$user->id}}" onclick="return checkDelete()"><i class="fa fa-trash"></i> Xóa </a>
                </td>
                <td>
                  <a href="admin/ssl/{{$user->id}}"><i class="fa fa-pencil"></i> SSL đã lưu </a>
                </td>
                <td>
                  <a href="admin/ssl_all/{{$user->id}}"><i class="fa fa-pencil"></i> All SSL </a>
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
@endsection

@section('script')
<script src="public/admin_asset/vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="public/admin_asset/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="public/admin_asset/vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="public/admin_asset/vendors/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="public/admin_asset/vendors/iCheck/icheck.min.js"></script>
<!-- Datatables -->
<script src="public/admin_asset/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="public/admin_asset/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="public/admin_asset/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<script src="public/admin_asset/vendors/jszip/dist/jszip.min.js"></script>
<script src="public/admin_asset/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="public/admin_asset/vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- Custom Theme Scripts -->
<script src="public/admin_asset/build/js/custom.min.js"></script>
<script language="JavaScript" type="text/javascript"> 
  function checkDelete(){ 
    return confirm('Bạn có chắc chắn muốn xóa danh mục này?'); 
  } 
</script>
@endsection