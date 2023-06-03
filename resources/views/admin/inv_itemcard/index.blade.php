@extends('layouts.admin')
@section('title')
ضبط الاصناف
@endsection
@section('contentheader')
الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('inv_itemcard.index') }}"> الاصناف </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات الاصناف</h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.inv_itemcard.ajax_search') }}">
            <a href="{{ route('inv_itemcard.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div class="row">
               <div class="col-md-4">
                  
                  <input checked type="radio" name="searchbyradio" id="searchbyradio" value="barcode"> باركود
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="item_code"> بالكود
                  <input type="radio" name="searchbyradio" id="searchbyradio" value="name"> الاسم
                  <input style="margin-top: 7px;" type="text" id="search_by_text" placeholder=" اسم - باركود - كود الصنف" class="form-control"> 
               </div>
               <div class="col-md-4">
                    <div class="form-group">
                        <label>  بحث بنوع الصنف </label>
                        <select class="form-control" name="item_type_search" id="item_type_search">
                            <option value="all" selected="selected">بحث الكل </option>
                            <option  value="1">مخزني </option>
                            <option  value="2">استهلاكي بتاريخ صلاحية </option>
                            <option  value="3">عهده </option>

                        </select>
                        @error('item_type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label> بحث بفئة الصنف</label>
                        <select name="inv_itemcard_categories_id_search" id="inv_itemcard_categories_id_search" class="form-control ">
                           <option value="all" selected="selected">بحث الكل </option>
                            @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                                @foreach ($inv_itemcard_categories as $info )
                                    <option  value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('inv_itemcard_categories_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
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
                        <th>مسلسل</th>
                        <th>الاسم </th>
                        <th> النوع </th>
                        <th>الفئة </th>
                        <th>صنف الاب </th>
                        <th>وحدة الاب </th>
                        <th>صنف التجزئة </th>
                        <th>حالة التفعيل</th>

                        <th></th>

                     </thead>
                     <tbody>
                        @foreach ($data as $info )
                        <tr>
                           <td>{{ $i }}</td>
                           <td>{{ $info->name }}</td>
                           <td>@if($info->item_type==1) مخزني @elseif($info->item_type==2) استهلاكي بصلاحية @elseif($info->item_type==3) عهده @else غير محدد @endif</td>
                           <td>{{ $info->inv_itemcard_categories_name }}</td>
                           <td>{{ $info->parent_item_name }}</td>
                           <td>{{ $info->uom_name }}</td>
                           <td>{{ $info->retail_uom_name }}</td>



                           <td>@if($info->active==1) مفعل @else معطل @endif</td>

                           <td>
                              <a href="{{ route('inv_itemcard.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                              <a href="{{ route('inv_itemcard.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
                              <a href="{{ route('inv_itemcard.show',$info->id) }}" class="btn btn-sm   btn-info">عرض</a>
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
      <script src="{{asset('admin/js/inv_itemcard.js')}}"></script>
      
   @endsection
