@extends('layouts.admin')

@section('title')
الرئسية
@endsection

@section('contentheader')
الرئسية
@endsection
@section('contentheaderlink')
<a href="{{route('admin.dashboard')}}">الرئسية</a>
@endsection



@section('contentheaderactive')
عرض
@endsection

@section('content')
<div class="row" style="background-image: url({{asset('admin/dist/img/dash.jpg')}}); background-size:cover;min-height:600px; background-repeat:no-repeat"> </div>
@endsection


