@extends('layouts.admin')

@section('title')
تعديل الضبط العام
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
الضبط
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
                <h3 class="card-title card_title_center"> تعديل بيانات الضبط العام </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(@isset($data) && !@empty($data))
                <form action="{{route('admin.adminPanelSettings.update')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> اسم الشركة</label>
                                <input type="text" name="system_name" id="system_name" class="form-control" value="{{$data['system_name']}}" placeholder="ادخل اسم الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل ')" onchange="try{setCutomValidty('')}catch(e){}">
                                @error('system_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> عنوان الشركة</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{$data['address']}}" placeholder="ادخل عنوان الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل ')" onchange="try{setCutomValidty('')}catch(e){}">
                                @error('address')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> هاتف الشركة</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{$data['phone']}}" placeholder="ادخل هاتف الشركة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل ')" onchange="try{setCutomValidty('')}catch(e){}">
                                @error('phone')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> الحسابات الأب للعملاء بالشجرة المحاسبية</label>
                                <select name="customer_parent_account_number" id="customer_parent_account_number" class="form-control select2">
                                    <option value="">اختر الحساب </option>
                                    @if (@isset($parent_accounts) && !@empty($parent_accounts))
                                    @foreach ($parent_accounts as $info )

                                    <option @if(old('customer_parent_account_number',$data['customer_parent_account_number'])==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('customer_parent_account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> الحسابات الأب للموردين بالشجرة المحاسبية</label>
                                <select name="suppliers_parent_account_number" id="suppliers_parent_account_number" class="form-control select2">
                                    <option value="">اختر الحساب </option>
                                    @if (@isset($parent_accounts) && !@empty($parent_accounts))
                                    @foreach ($parent_accounts as $info )

                                    <option @if(old('suppliers_parent_account_number',$data['suppliers_parent_account_number'])==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('suppliers_parent_account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> الحسابات الأب للمناديب بالشجرة المحاسبية</label>
                                <select name="delegates_parent_account_number" id="delegates_parent_account_number" class="form-control select2 ">
                                    <option value="">اختر الحساب </option>
                                    @if (@isset($parent_accounts) && !@empty($parent_accounts))
                                    @foreach ($parent_accounts as $info )

                                    <option @if(old('delegates_parent_account_number',$data['delegates_parent_account_number'])==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('delegates_parent_account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label> الحسابات الأب للموظفين بالشجرة المحاسبية</label>
                                <select name="employees_parent_account_number" id="employees_parent_account_number" class="form-control select2 ">
                                    <option value="">اختر الحساب </option>
                                    @if (@isset($parent_accounts) && !@empty($parent_accounts))
                                    @foreach ($parent_accounts as $info )

                                    <option @if(old('employees_parent_account_number',$data['employees_parent_account_number'])==$info->account_number) selected="selected" @endif value="{{ $info->account_number }}"> {{ $info->name }} </option>
                                    @endforeach
                                    @endif
                                </select>
                                @error('employees_parent_account_number')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>



                        <div class="col-lg-12">
                            <div class="form-group">
                                <label> رساله تنبيه اعلى الشركة </label>
                                <input type="text" name="general_alert" id="general_alert" class="form-control" value="{{$data['general_alert']}}" placeholder="ادخل رسالة تنبيهااعلى شاشة" oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل ')" onchange="try{setCutomValidty('')}catch(e){}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <label> شعار الشركة </label>
                                <div class="image">
                                    <img class="custom_img" id="image" src="{{asset('admin/uploads').'/'.$data['photo']}}" alt="لوجو الشركة ">
                                    <br>
                                    <button style="margin-top: 10px;" type="button" class="btn btn-sm btn-danger" id="update_image">تغير الصورة </button>
                                    <button type="button" class="btn btn-sm btn-danger" style="display: none; margin-top: 10px;" id="cancel_update_image"> إلفاء </button>

                                </div>
                                <div id="oldimage"></div>
                            </div>
                        </div>


                        <div class="form-group text-center" style="margin-bottom: 50px; z-index: 10; position: fixed; top: 100px; left: 0px">
                            <button type="submit" class="btn btn-primary btn-sm"> حفظ التعديلات</button>
                        </div>
                    </div>
                </form>
                @else
                <div class="alert alert-danger">
                    عفوا لا توجد بيانات لعرضها
                </div>
                @endif



            </div>
        </div>
    </div>
    @endsection

    @section('script')

    <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
    @endsection