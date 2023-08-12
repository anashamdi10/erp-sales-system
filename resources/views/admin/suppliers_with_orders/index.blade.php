@extends('layouts.admin')
@section('title')
المشتريات
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
حركات المخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.suppliers_orders.index') }}"> فواتير المشتريات </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">فواتير المشتريات </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.suppliers_orders.ajax_search') }}">
            <a href="{{ route('admin.suppliers_orders.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">

            <div class="row">
               <div class="col-md-4"> 
                  <input  type="radio" checked name="searchbyradio" id="searchbyradio" value="auto_serial"> بالكود الالي
                  <input  type="radio" name="searchbyradio" id="searchbyradio" value="Doc_No"> بالكود بأصل الشراء 
                  <input style="margin-top: 6px !important;" type="text" id="search_by_text" class="form-control">

                  
               </div>


               <div class="col-md-4">
                  <div class="form-group ">
                     <label> بيانات الموردين</label>
                     <select name="supplier_code" id="supplier_code" class="form-control select2 ">
                        <option value="all">بحث بكل الموردين</option>
                        @if (@isset($suppliers) && !@empty($suppliers))
                        @foreach ($suppliers as $info )
                        <option value="{{ $info->supplier_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>

                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بيانات المخازن</label>
                     <select name="store_id" id="store_id" class="form-control select2 ">
                        <option value="all">بحث بكل الخزن</option>
                        @if (@isset($stores) && !@empty($stores))
                        @foreach ($stores as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>
                  
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بحث من تاريخ </label>
                     <input name="from_order_date" id="from_order_date" type="date" class="form-control" >
                  
                  </div>

               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بحث الى تاريخ </label>
                     <input name="to_order_date" id="to_order_date" type="date"  class="form-control" >
                  
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
                     <th>كود</th>
                     <th> المورد</th>
                     <th> تاريخ الفاتورة</th>
                     <th> نوع الفاتورة</th>
                     <th> الاجمالي الفاتورة</th>
                     <th>حالة الفاتورة</th>

                     <th></th>

                  </thead>
                  <tbody>
                     @foreach ($data as $info )
                     <tr>
                        <td>{{ $info->auto_serial }}</td>
                        <td>{{ $info->supplier_name }}</td>
                        <td>{{ $info->order_date }}</td>
                        <td>@if($info->pill_type==1) كاش @elseif($info->pill_type==2) أجل@else غير محدد @endif</td>
                        <td>{{ $info->total_cost }}</td>
                        <td>@if($info->is_approved==0) مفتوحة @else معتمدة @endif</td>


                        <td style="text-align: center;">
                           @if($info->is_approved==0)
                           <a href="{{ route('admin.suppliers_orders.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                           <a href="{{ route('admin.suppliers_orders.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
                           @endif
                           <a href="{{ route('admin.suppliers_orders.show',$info->id) }}" class="btn btn-sm   btn-info">الاصناف </a>
                        </td>
                     </tr>
                     @php
                     $i++;
                     @endphp
                     @endforeach
                  </tbody>
               </table>
               <br>
               <div>
                  {{ $data->links() }}
               </div>
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
<script src="{{asset('admin/js/suppliers_orders.js')}}"></script>
<script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script>
   $(function() {
      //Initialize Select2 Elements
      $('.select2').select2({
         theme: 'bootstrap4'
      });
   });
</script>

@endsection