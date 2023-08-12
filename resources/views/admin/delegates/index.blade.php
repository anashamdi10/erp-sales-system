@extends('layouts.admin')
@section('title')
المناديب
@endsection
@section('contentheader')
الحسابات المالية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.delegates.index') }}"> المناديب </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات المناديب </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.delegates.ajax_search') }}">
            <input type="hidden" id="ajax_show_details" value="{{ route('admin.delegates.show_details') }}">
            <a href="{{route('admin.delegates.create')}}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  <input type="radio" checked name="searchbyradio" id="searchbyradio" value="account_number"> برقم الحساب
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="code"> برقم المندوب
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
                     <th> رقم الحساب </th>
                     <th> الرصيد </th>
                     <th> العنوان </th>
                     <th> الجوال </th>

                     <th> ملاحظات </th>
                     <th>حالة التفعيل</th>

                     <th></th>

                  </thead>
                  <tbody>
                     @foreach ($data as $info )

                     <tr>

                        <td>{{ $info->name }}</td>
                        <td>{{ $info->delegate_code }}</td>
                        <td>{{ $info->	account_number }}</td>
                        <td>
                           @if($info->is_parent==0)
                           @if($info->current_blance > 0 )
                           مدين ب ({{ $info->current_blance *1 }}) جنيه
                           @elseif($info->current_blance < 0) دائن ب ({{ $info->current_blance *(-1) }}) جنيه @else متزن @endif @else من ميزان المراجعه @endif </td>
                        <td>{{ $info->	address }}</td>
                        <td>{{ $info->	phones }}</td>
                        <td>{{ $info->	notes }}</td>


                        <td @if($info->active==1) class = "bg-success text-center" @else class = "bg-danger text-center" @endif>@if($info->active==1) مفعل @else معطل @endif</td>

                        <td class="text-center">
                           <a href="{{ route('admin.delegates.edit',$info->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                           <button data-delegate_code="{{ $info->delegate_code}}" id="details_show" class="btn btn-sm btn-info" data-toggle="modal"> التفاصيل </button>

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

            <div class="modal fade " id="DelegatesDetailsModel">
               <div class="modal-dialog modal-xl">
                  <div class="modal-content bg-info">
                     <div class="modal-header ">
                        <h4 class="modal-title "> تفاصيل  المندوب </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span></button>
                     </div>
                     <div class="modal-body" id="DelegatesDetailsModaMlBody" style="background-color: white !important; color:black;">

                     </div>
                     <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
                     </div>
                  </div>
                  <!-- /.modal-content -->
               </div>
               <!-- /.modal-dialog -->
            </div>

         </div>
      </div>
   </div>
</div>
@endsection


@section('script')
<script src="{{asset('admin/js/delegates.js')}}"></script>

@endsection