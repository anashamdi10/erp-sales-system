@extends('layouts.admin')
@section('title')
الحسابات 
@endsection
@section('contentheader')
الحسابات
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.account_types.index') }}"> انواع الحسابات </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات انواع الحسابات</h3>
           
         </div>
         <!-- /.card-header -->
         <div class="card-body">         
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">
                     <th>مسلسل</th>
                     <th>اسم النوع</th>
                     <th>حاله التفعيل</th>
                     <th>هل يضاف من شاشه داهلية  </th>
                  </thead>
                  <tbody>

                     @foreach ($data as $info )
                     <tr>
                        <td>{{ $info->id }}</td>
                        <td>{{ $info->name }}</td>
                        <td>@if($info->active==1)  مفعل@else  غير مفعل @endif</td>
                        <td>@if($info->relatediternalaccounts==1) نعم ويضاق من شاشته @else لا ويضاف من شاشه الحسابات @endif</td>
                       
                     </tr>
                     @endforeach
                  </tbody>
               </table>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script src="{{asset('admin/js/inv_uoms.js')}}"></script>
@endsection