@extends('layouts.admin')
@section('title')
إضافة الموردين  
@endsection
@section('contentheader')
حسابات  
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.suppliers.index') }}">  الموردين </a>
@endsection

@section('contentheaderactive')
إضافة
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> إضافة حساب مورد جديد </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{route('admin.suppliers.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم  المورد</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> فئة المورد</label>
                        <select name="suppliers_categories_id" id="suppliers_categories_id" class="form-control ">
                            <option value="">اختر الفئة</option>
                            @if (@isset($suppliers_categories) && !@empty($suppliers_categories))
                                @foreach ($suppliers_categories as $info )
                                    <option @if(old('suppliers_categories_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('suppliers_categories_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> حاله الرصيد اول المدة</label>
                        <select name="start_balance_status" id="start_balance_status" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option @if(old('start_blance_status')==1) selected="selected" @endif value="1"> دائن</option>
                            <option @if(old('start_blance_status')==2) selected="selected" @endif value="2"> مدين</option>
                            <option @if(old('start_blance_status')==3) selected="selected" @endif value="3"> متزن</option>
                        </select>
                        @error('start_balance_status')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> عنوان  العميل</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{old('address')}}">
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> رصيد اول المده للحساب </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="start_balance" id="start_balance" class="form-control" value="{{old('start_blance')}}">
                        @error('start_balance')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>  ملاحظات </label>
                        <input type="text" name="notes" id="notes" class="form-control" value="{{old('notes')}}">
                        @error('notes')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group">
                        <label> حاله التفعيل</label>
                        <select class="form-control" name="active" id="active">
                            <option value="" selected="selected">اختر الحاله </option>
                            <option @if(old('active')==1 || old('active') =="")) selected="selected" @endif value="1">نعم </option>
                            <option @if(old('active')==0 and old('active'!='' )) selected="selected" @endif value="0">لا </option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group text-center" style="margin-bottom: 50px;">
                <button id="to_add_item_card" type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                <a href="{{route('admin.suppliers.index')}}" class="btn btn-sm btn-danger">الغاء</a>
            </div>

        </form>
    </div>
</div>
</div>

@endsection



@section('script')
<script src="{{asset('admin/js/customer.js')}}"></script>


@endsection