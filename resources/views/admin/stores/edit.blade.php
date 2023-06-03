@extends('layouts.admin')

@section('title')
 تعديل  المخازن 
@endsection

@section('contentheader')
المخازن
@endsection
@section('contentheaderlink')
<a href="{{route('admin.stores.index')}}"> المخازن</a>
@endsection



@section('contentheaderactive')
تعديل
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">  تعديل بيانات المخزن  </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    
                    <form action="{{ route('admin.stores.update',$data['id']) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label> اسم المخزن </label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name', $data['name'])}}"  placeholder="ادخل اسم المخزن  ">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                        <div class="form-group">
                            <label> الهاتف  </label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{old('phone', $data['phone'])}}"  placeholder="ادخل  الهاتف  ">
                                @error('phone')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                        <div class="form-group">
                            <label> اسم العنوان </label>
                            <input type="text" name="address" id="address" class="form-control" value="{{old('address', $data['address'])}}"  placeholder="ادخل العنوان   ">
                                @error('address')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                       
                        
                        <div class="form-group">
                            <label>    حاله التفعيل</label>
                            <select class="form-control" name="active" id="active">
                                <option value="">اختر الحاله  </option>
                                <option 
                                    @if(old('active',$data['active'])==1) selected="selected" @endif
                                    value="1">نعم  
                                </option>
                                <option 
                                    @if(old('active',$data['active'])==0) selected="selected" @endif
                                
                                    value="0">لا 
                                </option>
                            </select>
                                @error('active')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                        

                        <div class="form-group text-center" style="margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary btn-sm"> حفظ التعديلات</button>
                            <a href="{{route('admin.stores.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection