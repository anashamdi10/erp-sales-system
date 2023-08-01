@extends('layouts.admin')
@section('title')
المشتريات
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
حركات المخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.suppliers_orders.index') }}"> فواتير المشتريات </a>
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
                <h3 class="card-title card_title_center">    إضافة  فاتورة مشتريات جديدة </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{route('admin.suppliers_orders.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>  تاريخ الفاتورة</label>
                            <input name="order_date" id="order_date" type="date" value="@php echo date("Y-m-d"); @endphp" class="form-control" value="{{ old('order_date') }}"    >
                            @error('order_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>  رقم الفاتورة المسجل بأصل فاتورة المشتريات</label>
                            <input type="text" name="Doc_No" id="Doc_No" class="form-control" value="{{old('Doc_No')}}" >
                        
                        </div>
                        <div class="form-group">
                            <label> بيانات  الموردين</label>
                            <select name="supplier_code" id="supplier_code" class="form-control select2 ">
                                <option  value="">اختر المورد</option>
                                @if (@isset($suppliers) && !@empty($suppliers))
                                    @foreach ($suppliers as $info )
                                        <option  value="{{ $info->supplier_code }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('supplier_code')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        
                    
                       
                        <div class="form-group">
                            <label>     نوع الفاتورة</label>
                            <select class="form-control" name="pill_type" id="pill_type">
                                <option  value=""  selected = "selected">اختر النوع  </option>
                                <option    value="1"> كاش </option>
                                <option   value="2">اجل </option>
                            </select>
                                @error('pill_type')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                        </div>





                        <div class="form-group">
                            <label> بيانات  المخازن</label>
                            <select name="store_id" id="store_id" class="form-control select2 ">
                                <option  value="">اختر المخزن المستلم للفاتورة</option>
                                @if (@isset($stores) && !@empty($stores))
                                    @foreach ($stores as $info )
                                        <option  value="{{ $info->id }}"> {{ $info->name }} </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('store_id')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>  ملاحظات</label>
                            <input type="text" name="notes" id="notes" class="form-control" value="{{old('notes')}}" >
                            @error('notes')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        

                        
                      

                        <div class="form-group text-center" style="margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                            <a href="{{route('admin.suppliers_orders.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection

    
@section('script')
    <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
                    //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }); 
    </script>
    
@endsection