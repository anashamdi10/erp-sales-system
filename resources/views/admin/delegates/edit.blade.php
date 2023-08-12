@extends('layouts.admin')
@section('title')
تعديل بيانات المندوب
@endsection
@section('contentheader')
الحسابات المالية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.customer.index') }}"> العملاء </a>
@endsection


@section('contentheaderactive')
تعديل
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> تعديل بيانات المندوب </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{route('admin.delegates.update',$data['id'])}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم العميل </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name',$data['name'])}}">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> نوع العمولة </label>
                        <select class="form-control" name="percent_type" id="percent_type">
                            <option value="0" selected="selected">اختر الحاله </option>
                            <option value="1">اجر ثابت </option>
                            <option value="2">نسبة </option>
                        </select>
                        @error('percent_type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label> نسبة العمولة المندوب بالمبيعات قطاعي </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_kataei" id="percent_sales_commission_kataei" class="form-control" value="{{old('percent_sales_commission_kataei',$data['percent_sales_commission_kataei'])}}">
                        @error('percent_sales_commission_kataei')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> نسبة العمولة المندوب بالمبيعات نص جملة</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_nosjomla" id="percent_sales_commission_nosjomla" class="form-control" value="{{old('percent_sales_commission_kataei',$data['percent_sales_commission_kataei'])}}">
                        @error('percent_sales_commission_nosjomla')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> نسبة العمولة المندوب بالمبيعات جملة </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_jomla" id="percent_sales_commission_jomla" class="form-control" value="{{old('percent_sales_commission_jomla',$data['percent_sales_commission_jomla'])}}">
                        @error('percent_sales_commission_jomla')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> نسبة المندوب بالتحصيل الفواتير اجل </label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_collect_commiission" id="percent_collect_commiission" class="form-control" value="{{old('percent_collect_commiission   ',$data['percent_collect_commiission'])}}">
                        @error('percent_collect_commiission')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> العنوان </label>
                        <input type="text" name="address" id="address" class="form-control" value="{{old('address',$data['address'])}}">
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> الجوال</label>
                        <input type="text" name="phones" id="phones" class="form-control" value="{{old('phones',$data['phones'])}}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> ملاحظات </label>
                        <input type="text" name="notes" id="notes" class="form-control" value="{{old('notes',$data['notes'])}}">
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
                <a href="{{route('admin.delegates.index')}}" class="btn btn-sm btn-danger">الغاء</a>
            </div>

        </form>
    </div>
</div>
</div>

@endsection



@section('script')
<script src="{{asset('admin/js/accounts.js')}}"></script>


@endsection