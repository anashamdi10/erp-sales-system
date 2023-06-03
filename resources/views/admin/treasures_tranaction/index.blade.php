@extends('layouts.admin')
@section('title')
 شاشة تحصيل النقدية
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
الحسابات
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.collect_tranaction.index') }}"> شاشة تحصيل النقدية </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات  حركة تحصيل النقدية بالنظام </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
         </div>
         <!-- /.card-header -->
         <div class="card-body">


         @if(!empty($check_exsits_shifts))
            <form action="{{route('admin.suppliers_orders.store')}}" method="post">
               @csrf
               
               <div class="row ">
                  <div class="col-lg-3">
                     <div class="form-group">
                        <label> بيانات  الموردين</label>
                        <select name="treasures_id" id="treasures_id" class="form-control  ">
                              <option  value="{{ $check_exsits_shifts['id'] }}"> {{ $check_exsits_shifts['treasure_name'] }} </option>
                        </select>
                        @error('treasures_id ')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>   الحسابات المالية</label>
                        <select name="account_number" id="account_number" class="form-control select2 ">
                           <option value="">اختر الحساب المالي المحصل منه</option>
                           @if (@isset($accounts) && !@empty($accounts))
                           @foreach ($accounts as $info )
                           <option data-type="{{ $info->account_type }}"    @if(old('account_number')==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} ({{ $info->account_type_name }}) </option>
                           @endforeach
                           @endif
                        </select>
                        @error('account_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                     </div>
                   </div>
               </div>

            </form>
         @endif   




           
            <div id="ajax_responce_serarchDiv">
               @if (@isset($data) && !@empty($data) && count($data) >0 )
               @php
               $i=1;   
               @endphp
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">
                     <th>مسلسل</th>
                     <th>اسم الخزنة</th>
                     <th>هل رئيسية</th>
                     <th>اخر ايصال صرف</th>
                     <th>اخر ايصال تحصيل</th>
                     <th>حالة التفعيل</th>
                     <th></th>
                  </thead>
                  <tbody>
                     @foreach ($data as $info )
                     <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $info->name }}</td>
                        <td>@if($info->is_master==1) رئيسية @else فرعية @endif</td>
                        <td>{{ $info->last_isal_exchange }}</td>
                        <td>{{ $info->last_isal_collect }}</td>
                        <td>@if($info->active==1) مفعل @else معطل @endif</td>
                        <td>
                           <a href="{{ route('admin.treasures.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>   
                           <a href="{{ route('admin.treasures.details',$info->id) }}"  class="btn btn-sm  btn-info">المزيد</a>   
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
    <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
                    //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }); 
    </script>
    
@endsection