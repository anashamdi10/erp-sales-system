@extends('layouts.admin')
@section('title')
إضاقه حساب مالي
@endsection
@section('contentheader')
الحسابات المالية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.accounts.index') }}"> الحسابات المالية </a>
@endsection


@section('contentheaderactive')
إضافة
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> إضافة حساب مالي جديد </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{route('admin.accounts.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم الحساب المالي</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> نوع الحساب</label>
                        <select name="account_type" id="account_type" class="form-control ">
                            <option value="">اختر النوع</option>
                            @if (@isset($account_type) && !@empty($account_type))
                            @foreach ($account_type as $info )
                            <option @if(old('account_type')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('account_types')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label> هل الحساب أب</label>
                        <select name="is_parent" id="is_parent" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option @if(old('is_parent')==1) selected="selected" @endif value="1"> نعم</option>
                            <option @if(old('is_parent')==0 and old('is_parent')!="" ) selected="selected" @endif value="0"> لا</option>
                        </select>
                        @error('is_parent')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" id="parentDiv" @if(old('is_parent')==1||old('is_parent')=='' ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> الحسابات الأب</label>
                        <select name="parent_account_number" id="parent_account_number" class="form-control ">
                            <option value="">اختر الحساب الاب</option>
                            @if (@isset($parent_acounts) && !@empty($parent_acounts))
                            @foreach ($parent_acounts as $info )
                           
                            <option @if(old('parent_account_number')==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('parent_account_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label> حاله الرصيد اول المدة</label>
                        <select name="start_balance_status" id="start_balance_status" class="form-control">
                            <option value="">اختر الحالة</option>
                            <option @if(old('start_balance_status')==1) selected="selected" @endif value="1"> دائن</option>
                            <option @if(old('start_balance_status')==2) selected="selected" @endif value="2"> مدين</option>
                            <option @if(old('start_balance_status')==3) selected="selected" @endif value="3"> متزن</option>
                        </select>
                        @error('start_balance_status')
                        <span class="text-danger">{{ $message }}</span>
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
                        <select class="form-control" name="is_archived" id="is_archived">
                            <option value="" selected="selected">اختر الحاله </option>
                            <option @if(old('is_archived')==0 and old('is_archived'!='' )) selected="selected" @endif value="0">نعم </option>
                            <option @if(old('is_archived')==1 ) selected="selected" @endif value="1">لا </option>
                        </select>
                        @error('is_archived')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group text-center" style="margin-bottom: 50px;">
                <button id="to_add_item_card" type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                <a href="{{route('admin.accounts.index')}}" class="btn btn-sm btn-danger">الغاء</a>
            </div>

        </form>
    </div>
</div>
</div>

@endsection



@section('script')
<script src="{{asset('admin/js/accounts.js')}}"></script>


@endsection