@extends('layouts.admin')
@section('title')
الحسابات
@endsection
@section('contentheader')
الحسابات المالية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.accounts.index') }}"> الحسابات المالية </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات شجرة المحاسبية ( الحسابات المالية ) </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.accounts.ajax_search') }}">
            <a href="{{ route('admin.accounts.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div class="row">
               <div class="col-md-3">
                  <input type="radio" checked name="searchbyradio" id="searchbyradio" value="account_number"> برقم الحساب
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="name"> بالاسم
                  <input style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder=" اسم  - رقم الحساب" class="form-control"> <br>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label> بحث بنوع الحساب</label>
                     <select name="account_type_search" id="account_type_search" class="form-control ">
                        <option value="all"> بحث بالكل</option>
                        @if (@isset($account_type) && !@empty($account_type))
                        @foreach ($account_type as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label> هل الحساب أب</label>
                     <select name="is_parent_search" id="is_parent_search" class="form-control">
                        <option value="all"> بحث بالكل</option>
                        <option value="1"> نعم</option>
                        <option value="0"> لا</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-group">
                     <label> حالة التفعيل </label>
                     <select name="active_search" id="active_search" class="form-control">
                        <option value="all"> بحث بالكل</option>
                        <option value="1"> مفعل</option>
                        <option value="0"> معطل</option>
                     </select>
                  </div>
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
                     <th> رقم الحساب </th>
                     <th>النوع </th>
                     <th>هل الاب </th>
                     <th>الحساب الاب </th>
                     <th> الرصيد </th>
                     <th> التفعيل</th>

                     <th></th>

                  </thead>
                  <tbody>
                     @foreach ($data as $info )

                     <tr>

                        <td>{{ $info->name }}</td>
                        <td>{{ $info->	account_number }}</td>

                        <td>{{ $info->account_type_name }}</td>
                        <td>@if($info->is_parent==1) نعم @else لا @endif</td>
                        <td>{{$info->parent_account_name}}</td>
                        <td>
                           @if($info->is_parent==0)
                           @if($info->current_blance > 0 )
                           مدين ب ({{ $info->current_blance *1 }}) جنيه
                           @elseif($info->current_blance < 0) دائن ب ({{ $info->current_blance *(-1) }}) جنيه @else متزن @endif @else من ميزان المراجعه @endif </td>


                        <td @if($info->active==1) class = "bg-success text-center" @else class = "bg-danger text-center" @endif >@if($info->active==1) مفعل @else معطل @endif</td>

                        <td>
                           @if($info->relatediternalaccounts == 0)
                           <a href="{{ route('admin.accounts.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                           @else
                           <span>يعدل من شاشته </span>
                           @endif

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
<script src="{{asset('admin/js/accounts.js')}}"></script>

@endsection