@extends('layouts.admin')
@section('title')
المبيعات
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('contentheader')
فواتير المبيعات
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.SalesInvoices.index') }}"> المبيعات </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">فواتير المبيعات للعملاء </h3>
        <input type="hidden" id="token_search" value="{{csrf_token() }}">
        <input type="hidden" id="ajax_search_url" value="{{ route('admin.SalesInvoices.ajax_search') }}">
        <input type="hidden" id="ajax_get_uoms" value="{{ route('admin.SalesInvoices.get_item_uoms') }}">
        <input type="hidden" id="ajax_load_model_offer_price" value="{{ route('admin.SalesInvoices.load_model_offer_price') }}">
        <input type="hidden" id="ajax_get_inv_itemcard_batches" value="{{ route('admin.SalesInvoices.get_inv_itemcard_batches') }}">
        <input type="hidden" id="ajax_get_item_price" value="{{ route('admin.SalesInvoices.get_item_price') }}">
        <input type="hidden" id="ajax_add_sales_row" value="{{ route('admin.SalesInvoices.add_sales_row') }}">
        <input type="hidden" id="ajax_load_model_sales_invoice" value="{{ route('admin.SalesInvoices.load_model_sales_invoice') }}">
        <input type="hidden" id="ajax_do_add_new_sales_invoice" value="{{ route('admin.SalesInvoices.do_add_new_sales_invoice') }}">
        <input type="hidden" id="ajax_do_update_sales_invoice" value="{{ route('admin.SalesInvoices.do_update_sales_invoice') }}">
        <input type="hidden" id="ajax_add_items_to_invoice" value="{{ route('admin.SalesInvoices.add_items_to_invoice') }}">
        <input type="hidden" id="ajax_add_new_item_sales_row" value="{{ route('admin.SalesInvoices.add_new_item_sales_row') }}">
        <input type="hidden" id="ajax_delete_item_sales_details_row" value="{{ route('admin.SalesInvoices.delete_item_sales_details_row') }}">
        <!-- reaload_items_in_invoice -->
        <input type="hidden" id="ajax_reload_invoice_details" value="{{ route('admin.SalesInvoices.reload_invoice_details') }}">
        <input type="hidden" id="ajax_do_close_and_approve" value="{{ route('admin.SalesInvoices.do_close_and_approve') }}">
        <input type="hidden" id="ajax_load_usershiftDiv" value="{{ route('admin.SalesInvoices.load_usershiftDiv') }}">
        <input type="hidden" id="ajax_sales_invoice_details" value="{{ route('admin.SalesInvoices.load_sales_invoice_details') }}">
        <input type="hidden" id="ajax_add_new_customer" value="{{ route('admin.SalesInvoices.add_new_customer') }}">
        <input type="hidden" id="ajax_reload_customers" value="{{ route('admin.SalesInvoices.reload_customers') }}">
        <input type="hidden" id="ajax_customer_search" value="{{ route('admin.SalesInvoices.customers_search') }}">
        <input type="hidden" id="ajax_customer_search_update" value="{{ route('admin.SalesInvoices.customers_search_update') }}">
        <input type="hidden" id="ajax_item_card_search_update" value="{{ route('admin.SalesInvoices.item_card_search') }}">
        <input type="hidden" id="ajax_customer_update" value="{{ route('admin.SalesInvoices.customer_update') }}">
        
        
        <button id="AddNewOfferPrice_show" class="btn btn-sm btn-success" data-toggle="modal"> عرض سعر </button>
        <button id="AddNewSalesInvoice" class="btn btn-sm btn-primary" data-toggle="modal"> فاتورة مبيعات </button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <input checked type="radio" name="searchbyradio" id="searchbyradio" value="auto_serial"> بالكود
                <input type="radio" name="searchbyradio" id="searchbyradio" value="customer_code"> كود العميل
                <input type="radio" name="searchbyradio" id="searchbyradio" value="account_number"> رقم الحساب

                <input style="margin-top: 8px !important;" type="text" id="search_by_text" placeholder="" class="form-control"> <br>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بالعملاء</label>
                    <select name="customer_code_search" id="customer_code_search" class="form-control select2">
                        <option value="all">بحث بكل العملاء</option>
                        <option value="without"> بدون عميل (طياري)</option>

                        @if (@isset($customers) && !@empty($customers))
                        @foreach ($customers as $info )
                        <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بالمناديب</label>
                    <select name="delegates_code_search" id="delegates_code_search" class="form-control select2">
                        <option value="all">بحث بكل المناديب</option>
                        @if (@isset($delegates) && !@empty($delegates))
                        @foreach ($delegates as $info )
                        <option value="{{ $info->delegate_code }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بفئات الفواتير</label>
                    <select name="Sales_matrial_types_search" id="Sales_matrial_types_search" class="form-control select2">
                        <option value="all">بحث بكل الفئات</option>
                        @if (@isset($Sales_material_type) && !@empty($Sales_material_type))
                        @foreach ($Sales_material_type as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث نوع الفواتير</label>
                    <select name="pill_type_search" id="pill_type_search" class="form-control select2">
                        <option value="all">بحث بكل الانواع</option>
                        <option value="1"> كاش </option>
                        <option value="2"> اجل </option>

                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> نوع الخصم </label>
                    <select class="form-control" name="discount_type_search" id="discount_type_search">
                        <option value="all"> بحث بكل الانواع</option>
                        <option value="without">لايوجد خصم</option>
                        <option value="1"> نسبة مئوية</option>
                        <option value="2"> قيمة يدوي</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> حالة الاعتماد </label>
                    <select class="form-control" name="is_approved_search" id="is_approved_search">
                        <option value="all"> بحث بكل الحالات</option>
                        <option value="0"> مفتوحة</option>
                        <option value="1"> معتمدة ومرحلة</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> من تاريخ فاتورة</label>
                    <input name="invoice_date_from" id="invoice_date_from" class="form-control" type="date" value="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> الي تاريخ فاتورة </label>
                    <input name="invoice_date_to" id="invoice_date_to" class="form-control" type="date" value="">
                </div>
            </div>

        </div>


    </div>

    <div id="ajax_responce_serarchDiv">
        @if (@isset($data) && !@empty($data) && count($data) >0 )
        @php
        $i=1;
        @endphp
        <table id="example2" class="table table-bordered table-hover">
            <thead class="custom_thead">
                <th>كود</th>
                <th> تاريخ الفاتورة</th>
                <th> العميل</th>
                <th> فئة الفاتورة</th>
                <th> نوع الفاتورة</th>

                <th> اجمالي الفاتورة</th>
                <th>حالة الفاتورة</th>

                <th></th>

            </thead>
            <tbody>
                @foreach ($data as $info )
                <tr>
                    <td>{{ $info->auto_serial }}</td>
                    <td>{{ $info->invoice_date }}</td>
                    <td>{{ $info->customer_name }}</td>
                    <td>{{ $info->material_types_name }}</td>
                    <td>@if($info->pill_type==1) كاش @elseif($info->pill_type==2) اجل @else غير محدد @endif</td>
                    <td>{{ $info->total_cost*(1) }}</td>

                    <td>@if($info->is_approved==1) معتمدة @else مفتوحة @endif</td>

                    <td style="text-align: center;">

                        @if($info->is_approved==0)
                        <button data-auto_serial="{{$info->auto_serial}}" class="btn btn-sm load_update_sales_invoice  btn-primary">تعديل</button>
                        <a href="{{ route('admin.SalesInvoices.delete_invoice',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>

                        @endif

                        <button data-autoserial="{{ $info->auto_serial }}" id='load_invoice_details_modal' class="btn btn-sm load_invoice_details_modal btn-info">عرض</button>

                    </td>


                </tr>
                @php
                $i++;
                @endphp
                @endforeach
            </tbody>
        </table>
        <br>
        <div>
            {{ $data->links() }}
        </div>
        @else
        <div class="alert alert-danger">
            عفوا لاتوجد بيانات لعرضها !!
        </div>
        @endif
    </div>
</div>


<div class="modal fade  " id="AddNewOfferPriceModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title text-center"> عرض سعر </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="AddNewOfferPriceModaMlBody" style="background-color: white !important; color:black;">



            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade  " id="AddNewSalesInvoiceModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header">
                <h4 class="modal-title text-center"> إضافة فاتورة مبيعات </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="AddNewSalesInvoiceModaMlBody" style="background-color: white !important; color:black;">



            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade  " id="UpdateSalesInvoiceModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header ">
                <h4 class="modal-title "> تحديث فاتورة مبيعات </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="UpdateSalesInvoiceModaMlBody" style="background-color: white !important; color:black;">



            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="DetailsSalesInvoiceModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header ">
                <h4 class="modal-title "> تفاصيل فاتورة مبيعات </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="DetailsSalesInvoiceModaMlBody" style="background-color: white !important; color:black;">

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="AddNewCustomereModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-info">
            <div class="modal-header ">
                <h4 class="modal-title ">إضافة عميل جديد </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="AddNewCustomerBody" style="background-color: white !important; color:black;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> اسم العميل</label>
                            <input autofocus type="text" name="name" id="name" class="form-control">
                            @error('name')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> حاله الرصيد اول المدة</label>
                            <select name="start_balance_status" id="start_balance_status" class="form-control">
                                <option value="">اختر الحالة</option>
                                <option value="1"> دائن</option>
                                <option value="2"> مدين</option>
                                <option selected='selected' value="3"> متزن</option>
                            </select>
                            @error('start_blance_status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> عنوان العميل</label>
                            <input type="text" name="address" id="address" class="form-control">

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> رصيد اول المده للحساب </label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="start_balance" id="start_balance" class="form-control" value="0">
                            @error('start_blance')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> الجوال</label>
                            <input type="text" name="phones" id="phones" class="form-control">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label> ملاحظات </label>
                            <input type="text" name="notes" id="notes" class="form-control">

                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label> حاله التفعيل</label>
                            <select class="form-control" name="active" id="active">
                                <option value="1">نعم </option>
                                <option value="0">لا </option>
                            </select>
                            @error('active')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group text-center" style="margin-top: 35px;">
                            <button id="add_new_customer" type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


</div>


@endsection
@section('script')


<script src="{{asset('admin/js/sales_invoice.js')}}"></script>
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