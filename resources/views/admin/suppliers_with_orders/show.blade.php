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
عرض التفاصيل
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">

                    <div class="col-lg-6 " style="left: 400px;">
                        <h3 class="card-title"> تفاصيل فاتورة المشتريات </h3>
                    </div>
                    @if($data['is_approved']==0)
                        <div class="col-lg-6 text-right ">
                        <a href="{{route('admin.suppliers_orders.edit',$data['id'])}}" class="btn btn-sm btn-success" style="margin-right: 50px;"> تعديل</a>
                        <a href="{{ route('admin.suppliers_orders.delete',$data['id']) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
                        <button id="load_close_approve_invoice" class="btn btn-sm   btn-primary">الاعتماد</button>
                    @endif    

                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="ajax_responce_serarchDivparentpill">
                    @if(@isset($data) && !@empty($data))

                        <div class="row">

                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label>كود الفاتورة الألي</label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text"> <i class="fa fa-hashtag"></i></span>
                                    <div class="form-control">{{ $data['auto_serial']}}</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label>كود الفاتورة بأصل فاتورة المشتريات </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text"> <i class="fa fa-hashtag"></i></span>
                                    <div class="form-control">{{ $data['Doc_No']}}</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> تاريخ الفاتروة </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <div class="form-control">{{ $data['order_date']}}</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> اسم المورد </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    <div class="form-control">{{ $data['supplier_name']}}</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> نوع الفاتورة </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-money-bill"></i>
                                    </span>
                                    <div class="form-control"> @if($data['pill_type']==1) كاش @else اجل @endif</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> اسم المخزن </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-warehouse"></i>
                                    </span>
                                    <div class="form-control"> {{ $data['store_name']}}</div>
                                </div>
                            </div>


                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> إحمالي الفاتورة </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-money-bill"></i>
                                    </span>
                                    <div class="form-control"> {{ $data['total_befor_discount']*1 }}</div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label> الخصم على الفاتورة </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-money-bill"></i>
                                    </span>
                                    <div class="form-control">
                                        @if($data['discount_type'] != null)
                                            @if($data['discount_type'] == 1)

                                                خصم نسبة( {{$data['discount_percent']}} ) وقيمتها ( {{$data['discount_value']}})

                                            @else

                                                خصم يدوي وقيمته ( {{$data['discount_value']}})

                                            @endif

                                        @else 
                                            لا يوجد 
                                        @endif    

                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label>  نسبة القيمة المضافة  </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-money-bill"></i>
                                    </span>
                                    <div class="form-control">  
                                        @if($data['tax_percent']>0)
                                            بنسبة ( {{$data['tax_percent'] }} % ) وقيمتها ({{$data['tax_value']}})  
                                        @else
                                            لا يوجد
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3 col-lg-3">
                                <div>
                                    <label>  حالة الفاتورة </label>
                                </div>
                                <div class="input-group-prepend" style="width: 100%">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </span>
                                    <div class="form-control">  
                                        @if($data['is_approved']==1) مغلق ومؤرشف @else مفتوحة @endif
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                    @else
                        <div class="alert alert-danger">
                            عفوا لا توجد بيانات لعرضها
                        </div>
                    @endif
                </div>

                <!-- treasuries delivery -->
                <div class="card-header row">
                    <div class="col-lg-6 text-left">
                        <h5> الأصناف المضافة للفاتورة</h5>
                    </div>
                    <div class="col-lg-6 text-right">
                        @if($data['is_approved']==0)
                            <button type="button" class="btn btn-info" id="load_model_add_detailsBtn">
                                إضافة صنف للفاتورة
                            </button>
                        @endif
                    </div>
                </div>
                    <input type="hidden" id="token_search" value="{{csrf_token() }}">
                    <input type="hidden" id="ajax_get_item_uoms_url" value="{{ route('admin.suppliers_orders.get_item_uoms') }}">
                    <input type="hidden" id="ajax_add_new_details" value="{{ route('admin.suppliers_orders.ajax_add_new_details') }}">
                    <input type="hidden" id="ajax_reload_itemsdetails" value="{{ route('admin.suppliers_orders.reload_itemsdetails') }}">
                    <input type="hidden" id="ajax_reload_parent_pill" value="{{ route('admin.suppliers_orders.reload_parent_pill') }}">
                    <input type="hidden" id="ajax_load_edit_item_details" value="{{ route('admin.suppliers_orders.load_edit_item_details') }}">
                    <input type="hidden" id="ajax_load_model_add_details" value="{{ route('admin.suppliers_orders.load_model_add_details') }}">
                    <input type="hidden" id="ajax_edit_item_details" value="{{ route('admin.suppliers_orders.edit_item_details') }}">
                    <input type="hidden" id="ajax_load_model_approve_invoice" value="{{ route('admin.suppliers_orders.load_model_approve_invoice') }}">
                    <input type="hidden" id="ajax_load_usershiftDiv" value="{{ route('admin.suppliers_orders.load_usershiftDiv') }}">
                    <input type="hidden" id="autoserailparent" value="{{$data['auto_serial'] }}">

               
                <div id="ajax_responce_serarchDivDetails">
                    @if (@isset($details) && !@empty($details) && count($details) >0 )
                    @php
                    $i=1;
                    @endphp
                    
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>مسلسل</th>
                            <th> الصنف</th>
                            <th>الوحدة </th>
                            <th>الكمية</th>
                            <th>سعر الوحدة</th>
                            <th>الاجمالي</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($details as $info )
                            <tr>
                                <td>{{ $i }}</td>
                                <td>
                                    {{ $info->item_card_name }}
                                    @if($info->item_card_type==2)
                                    <br> تاريخ الانتاج {{$info-> production_date}}
                                    <br> تاريخ الانتهاء {{$info-> expire_date}}

                                    @endif
                                </td>
                                <td>{{ $info->uom_name }}</td>
                                <td>{{ $info->dliverd_quantity }}</td>
                                <td>{{ $info->unit_price }}</td>
                                <td>{{ $info->total_price }}</td>
                                <td>
                                    @if($data['is_approved']==0)
                                    <button data-id = "{{$info->id}}"  class="btn btn-sm load_edit_item_details  btn-primary">تعديل</button>
                                    <a href="{{ route('admin.suppliers_orders.delete_details',['id'=>$info->id,'id_parent'=>$data['id'] ]) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>

                                    @endif
                                </td>


                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    @else
                    <div class="alert alert-danger">
                        عفوا لاتوجد بيانات لعرضها !!
                    </div>
                    @endif
                </div>

                <!-- end treasuries delivery -->






            </div>
        </div>
    </div>


    <div class="modal fade " id="Add_item_model">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">إضافة اصناف للفاتورة </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="Add_item_model_body" style="background-color: white !important; color:black">
        
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-info">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <div class="modal fade " id="Edit_item_model">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title text-center">تحديث صنف بالفاتورة </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="Edit_item_model_body" style="background-color: white !important; color:black">
                  
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-info">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade " id="ModelApproveInvoice">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title text-center" style="width:100% ;">  اعتماد وترحيل فاتورة مشتريات </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ModelApproveInvoice_body" style="background-color: white !important; color:black">
        
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-info">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/suppliers_orders.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endsection