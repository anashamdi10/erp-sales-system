<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>@yield('title')</title>
  <!-- Font Awesome Icons -->
 
  <!-- Theme style -->
  <link rel="stylesheet" href="  {{asset('admin/dist/css/adminlte.min.css')}} ">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="   {{asset('admin/fonts/SansPro/SansPro.min.css')}}  ">
  <link rel="stylesheet" href=" {{asset('admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}  ">
  <link rel="stylesheet" href=" {{asset('admin/css/bootstrap_rtl-v4.2.1/custom_rtl.css')}}  ">
  <link rel="stylesheet" href=" {{asset('admin/css/mycustomstyle.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  @yield('css')
</head>
<body class="hold-transition sidebar-mini" >
<div class="wrapper">

  <!-- Navbar -->
    @include('admin.includes.navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.includes.sidebar')
  <!-- Content Wrapper. Contains page content -->
  @include('admin.includes.content')
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->

  <!-- Main Footer -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="  {{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}  "></script>
<!-- AdminLTE App -->
<script src="{{asset('admin/dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('admin/js/general.js')}}"></script>
@yield('script')
</body>
</html>
