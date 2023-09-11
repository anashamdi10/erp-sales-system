@extends('layouts.admin')
@section('title')
حركات مخزنية
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
أرصدة الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('inv_itemcard.index') }}"> الارصدة </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center"> مراة كميات الاصناف بالمخازن </h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.item_card_balance.ajax_search') }}">
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بيانات الاصناف</label>
                     <select name="item_code_search" id="item_code_search" class="form-control select2 ">
                        <option value="all"> بحث بكل الأصناف</option>
                        @if (@isset($item_card_items_search) && !@empty($item_card_items_search))
                        @foreach ($item_card_items_search as $info )
                        <option value="{{ $info->item_code }}"> {{ $info->name }}
                        </option>
                        @endforeach
                        @endif
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بيانات المخازن</label>
                     <select name="store_id_search" id="store_id_search" class="form-control">
                        <option value="all"> بحث بكل الأصناف</option>
                        @if (@isset($stores_search) && !@empty($stores_search))
                        @foreach ($stores_search as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                     </select>

                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label> حاله الباتشات </label>
                     <select class="form-control" name="batch_search" id="batch_search">
                        <option value="all"> عرض كل الباتشات </option>
                        <option value="1">الباتشات اللتي بها كمية فقظ </option>
                        <option value="2">الباتشات الفارغة </option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> انواع الباتشات </label>
                     <select class="form-control" name="TypeBatches" id="TypeBatches">
                        <option value="all"> عرض كل الانواع </option>
                        <option value="1">الباتشات غير الاستهلاكية </option>
                        <option value="2">الباتشات الاستهلاكية بتاريخ الانتاج </option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label> بحث بحالة الكميات </label>
                     <select class="form-control" name="BatchQuantityStatus" id="BatchQuantityStatus">
                        <option value="all"> عرض كل الانواع </option>
                        <option value="1"> اكبر من </option>
                        <option value="2"> اصغر من </option>
                        <option value="3"> يساوي </option>
                     </select>
                  </div>
               </div>
               <div class="col-md-4" id="BatchQuantitySearch" style="display: none;">
                  <div class="form-group">
                     <label> بحث بالكميات </label>
                     <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" id="BatchQuantity" class="form-control">

                  </div>
               </div>
            </div>

            <div id="ajax_responce_serarchDiv">
               @if (@isset($allitemscarddata) && !@empty($allitemscarddata) && count($allitemscarddata) >0 )
               @php
               $i=1;
               @endphp
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">
                     <th style="width: 10%;">كود الي</th>
                     <th style="width: 20%;">الاسم </th>
                     <th style="width: 70%;">الكميات بالمخازن</th>
                  </thead>
                  <tbody>
                     @foreach ($allitemscarddata as $info )
                     <tr>
                        <td>{{ $info->item_code }}</td>
                        <td>{{ $info->name }}</td>
                        <td>
                           كل الكميات بالمخازن ( {{ $info->all_quantity *1 }} {{ $info->uom_name  }} ) <br> <br>

                           @if( !@empty($info->allitembatches) and count($info->allitembatches) > 0)
                           <h3 style="font-size: 15px; text-align: center; color: brown;">تفاصيل كميات الصنف بالمخازن</h3>
                           <table id="example2" class="table table-bordered table-hover">
                              <thead class="bg-info">
                                 <td>رقم الباتش </td>
                                 <td> المخزن</td>
                                 <td> الكمية</td>
                              </thead>
                              <tbody>

                                 @foreach ($info->allitembatches as $det )
                                 <tr @if( $det->quantity==0) class="bg-warning" @endif>
                                    <td>{{$det->auto_serial}}</td>
                                    <td>{{$det->store_name}}</td>
                                    <td>
                                       عدد ({{$det->quantity}}) {{$info->uom_name}} بإجمالي تكلفة ({{$det->toatal_cost_price * 1 }} جنية ) <br>
                                       @if($info->item_type == 2)
                                       تاريخ الانتاج ( {{$det->production_date}}) <br>
                                       تاريخ الانتهاء ( {{$det->expired_date}} )
                                       @endif
                                       @if($info->does_has_retailunit == 1 )
                                       <br>
                                       <span style="color: brown;">مايوازي بوجدة التجزئة </span> <br>
                                       عدد ({{$det->retail_quantity}}) {{$info->retail_uom_name}} بإجمالي تكلفة ({{$det->toatal_cost_price * 1}}) <br>
                                       بسعر ({{$det->toatal_cost_price * 1 }} جنية ) لوحدة {{$info->retail_uom_name}}
                                       @endif
                                    </td>

                                 </tr>
                                 @endforeach

                              </tbody>
                           </table>

                           @else
                           <h3 style="font-size: 15px; text-align: center; color: brown;"> لا توجد كميات في المخازن </h3>
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
               {{ $allitemscarddata->links() }}
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
<script src="{{asset('admin/js/item_card_balance.js')}}"></script>
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