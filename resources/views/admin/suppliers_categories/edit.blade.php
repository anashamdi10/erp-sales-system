@extends('layouts.admin')
@section('title')
 تعديل فئات الموردين  
@endsection
@section('contentheader')
حسابات  
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.sales_material_types.index') }}"> فئات الموردين </a>
@endsection



@section('contentheaderactive')
تعديل
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">  تعديل بيانات الفئةالفواتير  </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{ route('admin.suppliers_categories.update',$data['id']) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label> اسم فئة الفواتير</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name', $data['name'])}}"  placeholder="ادخل اسم فئة الفواتير ">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                       
                        
                        <div class="form-group">
                            <label>  حاله التفعيل</label>
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
                            <a href="{{route('admin.suppliers_categories.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection