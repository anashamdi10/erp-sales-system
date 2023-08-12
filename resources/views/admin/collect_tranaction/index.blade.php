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

<div class="card">
   <div class="card-header">
      <h3 class="card-title card_title_center">بيانات حركة تحصيل النقدية بالنظام </h3>
      <input type="hidden" id="token_search" value="{{csrf_token() }}">
      <input type="hidden" id="ajax_search" value="{{ route('admin.collect_tranaction.search') }}">
      <input type="hidden" id="ajax_show_current_balance_account" value="{{ route('admin.collect_tranaction.show_current_balance_account') }}">

      @if(!empty($check_exsits_shifts))
      <form action="{{route('admin.collect_tranaction.store')}}" method="post">
         @csrf

         <div class="row ">
            <div class="col-md-4 ">
               <div class="form-group">
                  <label> تاريخ الحركة </label>
                  <input type="date" name="mov_date" id="mov_date" class="form-control" value="{{old('mov_date',date('Y-m-d'))}}">
                  @error('mov_date')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label> الحسابات المالية</label>
                  <select name="account_number" id="account_number" class="form-control select2 ">
                     <option value="">اختر الحساب المالي المحصل منه</option>
                     @if (@isset($accounts) && !@empty($accounts))
                     @foreach ($accounts as $info )
                     <option data-type="{{ $info->account_type }}" @if(old('account_number')==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} ({{ $info->account_type_name }}) </option>
                     @endforeach
                     @endif
                  </select>
                  @error('account_number')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div id='current_balanceDiv' class="col-md-4 " style="display: none;"></div>
            <div class="col-md-4">
               <div class="form-group">
                  <label> نوع الحركة </label>
                  <select name="mov_type" id="mov_type" class="form-control  ">
                     <option value="">اختر نوع الحركة </option>
                     @if (@isset($mov_type) && !@empty($mov_type))
                     @foreach ($mov_type as $info )
                     <option @if(old('mov_type')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                     @endforeach
                     @endif
                  </select>
                  @error('mov_type')
                  <span class="text-danger">{{ $message }}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-4" id="AccountStatusDiv" style="display: none;"></div>
            <div class="col-lg-4">
               <div class="form-group">
                  <label> بيانات الخزن </label>
                  <select name="treasures_id" id="treasures_id" class="form-control  ">
                     <option value="{{ $check_exsits_shifts['treasures_id'] }}"> {{ $check_exsits_shifts['treasure_name'] }} </option>
                  </select>
                  @error('treasures_id ')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-4 ">
               <div class="form-group">
                  <label> الرصيدالمتاح بالخزنة </label>
                  <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control" value="{{$check_exsits_shifts['treasures_balance_now']*1}}">
                  @error('treasuries_balance')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-4 ">
               <div class="form-group">
                  <label> قيمة المبلغ المحصل </label>
                  <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="money" id="money" class="form-control" value="{{old('money')}}">
                  @error('money')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-12 ">
               <div class="form-group">
                  <label> البيان </label>
                  <textarea class="form-control" name="bayan" id="bayan" rows="4" cols="10"> {{old('bayan' , 'تحصيل نظير')}}</textarea>
                  @error('byan')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
               </div>
            </div>
            <div class="col-md-12">
               <div class="form-group text-center">
                  <button id="btn_collect_now" type="submit" class="btn btn-success btn-sm"> تحصيل الان</button>
               </div>
            </div>
         </div>
      </form>
      @else
      <div class="alert alert-warning">
         تنبيه : لايوجد شيفت مفتوح لك لكي تتمكن من التحصيل !!
      </div>
      @endif
   </div>
   <div class="card-body">
      <div class="row">
         <div class="col-md-4">
            <input checked type="radio" name="searchbyradio" id="searchbyradio" value="auto_serial"> بالكود
            <input type="radio" name="searchbyradio" id="searchbyradio" value="isal_number"> الايصال
            <input type="radio" name="searchbyradio" id="searchbyradio" value="account_number"> رقم الحساب
            <input type="radio" name="searchbyradio" id="searchbyradio" value="shift_code"> شفت
            <input style="margin-top: 8px !important;" type="text" id="search_by_text" placeholder="" class="form-control"> <br>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> بحث بالحسابات المالية </label>
               <select name="account_number_search" id="account_number_search" class="form-control select2 ">
                  <option value="all"> بحث بالكل</option>
                  @if (@isset($accounts) && !@empty($accounts))
                  @foreach ($accounts as $info )
                  <option data-type="{{ $info->account_type }}" @if(old('account_number')==$info->account_number) selected="selected" @endif
                     value="{{ $info->account_number }}"> {{ $info->name }} ({{ $info->account_type_name }}) {{ $info->account_number }} </option>
                  @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> بحث بالحركة المالية </label>
               <select name="mov_type_search" id="mov_type_search" class="form-control  ">
                  <option value="all">بحث بالكل </option>
                  @if (@isset($mov_type) && !@empty($mov_type))
                  @foreach ($mov_type as $info )
                  <option @if(old('mov_type')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> بحث بالخزن</label>
               <select name="treasures_search" id="treasures_search" class="form-control select2">
                  <option value="all">بحث بكل الخزن </option>
                  @if (@isset($treasures) && !@empty($treasures))
                  @foreach ($treasures as $info )
                  <option value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> بحث بالمستخدمين</label>
               <select name="users_search" id="users_search" class="form-control select2">
                  <option value="all">بحث بكل المستخدمين</option>
                  @if (@isset($users) && !@empty($users))
                  @foreach ($users as $info )
                  <option value="{{ $info->id }}"> {{ $info->name }} </option>
                  @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> من تاريخ حركة </label>
               <input name="invoice_date_from_search" id="invoice_date_from_search" class="form-control" type="date" value="">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group">
               <label> الي تاريخ حركة </label>
               <input name="invoice_date_to_search" id="invoice_date_to_search" class="form-control" type="date" value="">
            </div>
         </div>
      </div>

   

      <div id="ajax_responce_serarchDiv">
         @if (@isset($data) && !@empty($data) && count($data) >0 )

         <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
               <th>كود الي</th>
               <th>رقم الايصال</th>
               <th> الخزنة</th>
               <th> المبلغ </th>
               <th> الحركة </th>
               <th> الحساب المالي </th>
               <th>البيان</th>
               <th> المستخدم</th>
               <th></th>
            </thead>
            <tbody>
               @foreach ($data as $info )
               <tr>
                  <td>{{ $info->auto_serial }}</td>
                  <td>{{ $info->isal_number }}</td>
                  <td>{{ $info->treasures_name}} <br>({{ $info->shift_code}}) </td>
                  <td>{{ $info->money * 1}}</td>
                  <td>{{ $info->mov_type_name }}</td>
                  <td>
                     @if($info->account_name != null)
                     {{ $info->account_name }}({{ $info->account_type_name }})
                     @else
                     بدون حساب مالي
                     @endif
                  </td>
                  <td>{{ $info->bayan}}</td>
                  <td>
                     @php
                     $dt=new DateTime($info->created_at);
                     $date=$dt->format("Y-m-d");
                     $time=$dt->format("h:i ");
                     $newDateTime=date("A",strtotime($time));
                     $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                     @endphp
                     {{ $date }} <br>
                     {{ $time }}
                     {{ $newDateTimeType }} <br>
                     <span>بواسطة</span>
                     {{ $info->added_by }}

                  </td>

                  <td>
                     <a href="{{ route('admin.treasures.edit',$info->id) }}" class="btn btn-sm  btn-primary">طباعه</a>
                     <a href="{{ route('admin.treasures.details',$info->id) }}" class="btn btn-sm  btn-info">المزيد</a>
                  </td>
               </tr>

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
@endsection
@section('script')
<script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/collect_tranaction.js')}}"></script>
<script>
   $(function() {
      //Initialize Select2 Elements
      $('.select2').select2({
         theme: 'bootstrap4'
      });
   });
</script>
@endsection