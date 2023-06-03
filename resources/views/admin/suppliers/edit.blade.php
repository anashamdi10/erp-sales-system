@extends('layouts.admin')
@section('title')
تعديل الموردين  
@endsection
@section('contentheader')
حسابات  
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.suppliers.index') }}">  الموردين </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> تعديل بيانات المورد </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
       
        <form action="{{route('admin.suppliers.update',$data['id'])}}" method="post" >
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم المورد </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name',$data['name'])}}">
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
                                    <option @if(old('suppliers_categories_id',$data['suppliers_categories_id'])==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
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
                        <label>  العنوان </label>
                        <input type="text" name="address" id="address" class="form-control" value="{{old('address',$data['address'])}}">
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>  ملاحظات </label>
                        <input type="text" name="notes" id="notes" class="form-control" value="{{old('notes',$data['notes'])}}">
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
                            <option {{ old('active',$data['active'])==1? 'selected':'' }} value="1">نعم </option>
                            <option {{ old('active',$data['active'])==0? 'selected':'' }} value="0">لا </option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group text-center" style="margin-bottom: 50px;">
                <button id="to_add_item_card" type="submit" class="btn btn-primary btn-sm"> تعديل </button>
                <a href="{{route('admin.customer.index')}}" class="btn btn-sm btn-danger">الغاء</a>
            </div>

        </form>
    </div>
</div>
</div>

@endsection



@section('script')
<script src="{{asset('admin/js/accounts.js')}}"></script>


@endsection