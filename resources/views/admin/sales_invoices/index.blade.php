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
        <input type="hidden" id="ajax_reload_invoice_details" value="{{ route('admin.SalesInvoices.reload_invoice_details') }}">
        <button id="AddNewOfferPrice_show" class="btn btn-sm btn-success" data-toggle="modal"> عرض سعر </button>
        <button id="AddNewSalesInvoice" class="btn btn-sm btn-primary" data-toggle="modal"> فاتورة مبيعات </button>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <div id="ajax_responce_serarchDiv">
            @if (@isset($data) && !@empty($data) && count($data) >0 )
            @php
            $i=1;
            @endphp
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th>كود</th>
                    <th> المورد</th>
                    <th> تاريخ الفاتورة</th>
                    <th> نوع الفاتورة</th>
                    <th> المخزن المستلم</th>
                    <th> اجمالي الفاتورة</th>
                    <th>حالة الفاتورة</th>

                    <th></th>

                </thead>
                <tbody>
                    @foreach ($data as $info )
                    <tr>
                        <td>{{ $info->auto_serial }}</td>
                        <td>{{ $info->supplier_name }}</td>
                        <td>{{ $info->order_date }}</td>
                        <td>@if($info->pill_type==1) كاش @elseif($info->pill_type==2) اجل @else غير محدد @endif</td>
                        <td>{{ $info->store_name }}</td>
                        <td>{{ $info->total_cost*(1) }}</td>

                        <td>@if($info->is_approved==1) معتمدة @else مفتوحة @endif</td>

                        <td>

                            @if($info->is_approved==0)
                            <button data-auto_serial="{{$info->auto_serial}}" class="btn btn-sm load_update_sales_invoice  btn-primary">تعديل</button>
                            <a href="{{ route('admin.suppliers_orders.delete',$info->id) }}" class="btn btn-sm are_you_shue  btn-danger">حذف</a>
                            @endif

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
                    <h4 class="modal-title text-center"> إضافة فاتورة مبيهات </h4>
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
                    <h4 class="modal-title "> تحديث فاتورة مبيهات </h4>
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