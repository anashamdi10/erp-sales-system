@extends('layouts.admin')

@section('title')
 تعديل  بيانات الخزنة 
@endsection

@section('contentheader')
الخزن 
@endsection
@section('contentheaderlink')
<a href="{{route('admin.adminPanelSettings.index')}}">الضبط</a>
@endsection



@section('contentheaderactive')
تعديل
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> تعديل بيانات  خزنة </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{ route('admin.treasures.update',$data['id']) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label> اسم الخزنة</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name', $data['name'])}}"  placeholder="ادخل اسم الخزنة ">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                        <div class="form-group">
                            <label> هل رئيسية  ؟</label>
                            <select class="form-control" name="is_master" id="is_master">
                                <option  value="">اختر النوع </option>
                                <option 
                                @if(old('is_master',$data['is_master'])==1) selected="selected" @endif
                                    value="1">نعم 
                                </option>
                                <option 
                                    @if(old('is_master',$data['is_master'])==0) selected="selected"  @endif
                                    value="0">لا 
                                </option>
                            </select>
                                @error('is_master')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>

                        <div class="form-group">
                            <label> اخر رقم ايصال صرف نقدية لهذه الخزنة</label>
                            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" name="last_isal_exchange"
                             id="last_isal_exchange" class="form-control"  placeholder="ادخل  اخر رقم ايصال صرف نقدية" 
                             value="{{old('last_isal_exchange', $data['last_isal_exchange'])}}">
                                @error('last_isal_exchange')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>
                        
                        <div class="form-group">
                            <label> اخر رقم ايصال تحصيل نقدية لهذه الخزنة</label>
                            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" name="last_isal_collect" 
                            id="last_isal_collect" class="form-control"  placeholder="ادخل  اخر رقم ايصال تحصيل نقدية"
                             value="{{old('last_isal_collect', $data['last_isal_collect'])}}">
                                @error('last_isal_collect')
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
                            <a href="{{route('admin.treasures.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection