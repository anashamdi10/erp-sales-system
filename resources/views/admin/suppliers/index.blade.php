@extends('layouts.admin')
@section('title')
الموردين
@endsection
@section('contentheader')
حسابات
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.suppliers.index') }}"> الموردين </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات الموردين </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.suppliers.ajax_search') }}">
            <a href="{{route('admin.suppliers.create')}}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  <input type="radio" checked name="searchbyradio" id="searchbyradio" value="account_number"> برقم الحساب
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="code"> برقم المورد
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="name"> بالاسم
                  <input style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder="   اسم  - رقم الحساب- كود العميل" class="form-control"> <br>
               </div>

            </div>

            <div id="ajax_responce_serarchDiv">
               @if (@isset($data) && !@empty($data) && count($data) >0 )
               @php
               $i=1;
               @endphp
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">

                     <th>الاسم </th>
                     <th>الكود </th>
                     <th>اسم الفئة </th>
                     <th> رقم الحساب </th>
                     <th> الرصيد </th>
                     <th> الجوال </th>
                     <th> ملاحظات </th>
                     <th>حالة التفعيل</th>

                     <th></th>

                  </thead>
                  <tbody>
                     @foreach ($data as $info )

                     <tr>

                        <td>{{ $info->name }}</td>
                        <td>{{ $info->supplier_code }}</td>
                        <td>{{ $info->categories_name }}</td>
                        <td>{{ $info->	account_number }}</td>
                        <td>
                           @if($info->is_parent==0)
                           @if($info->current_blance > 0 )
                           مدين ب ({{ $info->current_blance *1 }}) جنيه
                           @elseif($info->current_blance < 0) دائن ب ({{ $info->current_blance *(-1) }}) جنيه @else متزن @endif @else من ميزان المراجعه @endif </td>

                        <td>{{ $info->	phones }}</td>
                        <td>{{ $info->	notes }}</td>
                        <td @if($info->active==1) class = "bg-success text-center" @else class = "bg-danger text-center" @endif>@if($info->active==1) مفعل @else معطل @endif</td>

                        <td class="text-center">
                           <a href="{{ route('admin.suppliers.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                           <!-- <a href="{{ route ('admin.suppliers.delete',$info->id)}}" class="btn btn-sm  are_you_sure btn-danger">حذف</a> -->

                        </td>
                     </tr>
                     @php
                     $i++;
                     @endphp
                     @endforeach
                  </tbody>
               </table>
               <br>

               {{ $data->links() }}

               @else
               <div class="alert alert-danger">
                  عفوا لاتوجد بيانات لعرضها !!
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('admin/js/customer.js')}}"></script>

@endsection