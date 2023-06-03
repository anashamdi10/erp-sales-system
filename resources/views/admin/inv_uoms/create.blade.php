@extends('layouts.admin')
@section('title')
الوحدات
@endsection
@section('contentheader')
الوحدات
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.uoms.index') }}"> الوحدات </a>
@endsection
@section('contentheaderactive')
إضافة
@endsection


@section('contentheaderactive')
إضافة 
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">    إضافة  وحدة صنف جديدة </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{route('admin.uoms.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label> اسم الوحدة </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}"  placeholder="ادخل اسم المخزن  ">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                       
                        <div class="form-group">
                            <label>     نوع الوحدة</label>
                            <select class="form-control" name="is_master" id="is_master">
                                <option  selected = "selected">اختر النوع  </option>
                                <option  @if(old('is_master')==1) selected="selected" @endif value="1">وحدة اب  </option>
                                <option  @if(old('is_master')==0) selected="selected" @endif  value="0">وحده تجزئة </option>
                            </select>
                                @error('is_master')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>

                        
                        <div class="form-group">
                            <label>    حاله التفعيل</label>
                            <select class="form-control" name="active" id="active">
                                <option  selected = "selected">اختر الحاله  </option>
                                <option  @if(old('active')==1) selected="selected" @endif value="1">نعم  </option>
                                <option  @if(old('active')==0) selected="selected" @endif  value="0">لا </option>
                            </select>
                                @error('active')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>

                        <div class="form-group text-center" style="margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                            <a href="{{route('admin.stores.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection