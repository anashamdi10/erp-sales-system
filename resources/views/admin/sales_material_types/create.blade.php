@extends('layouts.admin')

@section('title')
 إضافة فئة فواتير جديدة
@endsection

@section('contentheader')
فئات الفواتير
@endsection
@section('contentheaderlink')
<a href="{{route('admin.sales_material_types.index')}}">فئات الفواتير</a>
@endsection



@section('contentheaderactive')
إضافة 
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">    إضافة فئة فواتير  جديدة </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{route('admin.sales_material_types.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label> اسم الفئةالفواتير</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}"  placeholder="ادخل اسم الفئة  ">
                                @error('name')
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
                            <a href="{{route('admin.sales_material_types.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection