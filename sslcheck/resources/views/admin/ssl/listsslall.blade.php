@extends('admin.layout.index')

@section('head')
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>List ssl</title>
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
            <h2>List ssl</h2>
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
                  <th>Domain</th>
                  <th>Người check</th>
                  <th>Có SSL không?</th>
                  <th>Đã lưu?</th>
                  <th>Ngày khởi tạo</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ssl_all as $ssl)
                <tr>
                  <td>{{$ssl->id}}</td>
                  <td>{{$ssl->domain}}</td>
                  <td>
                    {{$ssl->users->email}}
                  </td>
                  <td>
                    @if($ssl->has_ssl == 1)
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-close"></i>
                    @endif
                  </td>
                  <td>
                    @if($ssl->is_stored == 1)
                    <i class="fa fa-check"></i>
                    @else
                    <i class="fa fa-close"></i>
                    @endif
                  </td>
                  <td>
                    {{date('d-m-Y H:i:s', strtotime($ssl->created_at))}}
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