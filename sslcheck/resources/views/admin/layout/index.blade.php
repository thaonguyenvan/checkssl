<!DOCTYPE html>
<html lang="en">
  @yield('head')
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        @include('admin.layout.left-panel')
        <!-- top navigation -->
        @include('admin.layout.top-nav')
        <!-- /top navigation -->

        <!-- page content -->
        @yield('content')
        <!-- /page content -->
        <!-- footer content -->
        <footer>
          <div class="pull-right">
            CopyRight © 2019 - All Rights Reserved. <a href="https://supportdao.io">SupportDao</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    @yield('script')
  
  </body>
</html>