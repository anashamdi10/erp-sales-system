@if($invoice_data['is_approved'] == 0)

<input type="hidden" id="invoice_auto_serial" value="{{$invoice_data['auto_serial']}}" />

@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label> تاريخ الفاتورة </label>
            <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="{{$invoice_data['invoice_date']}}">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label> فئات الفواتير</label>
            <select name="sales_material_type" id="sales_material_type" class="form-control select2 ">
                <option value=""> اختر فئة الفاتورة</option>
                @if (@isset($Sales_material_type) && !@empty($Sales_material_type))
                @foreach ($Sales_material_type as $info )
                <option @if($invoice_data['sales_material_type']==$info->id ) selected @endif value="{{ $info->id }}">
                    {{ $info->name }}
                </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label> هل يوجد عميل </label>
            <select class="form-control" name="is_has_customer" id="is_has_customer">
                <option @if($invoice_data['is_has_customer']==1 ) selected @endif value="1" selected>نعم يوجد عميل
                </option>
                <option @if($invoice_data['is_has_customer']==0 ) selected @endif value="0">لا يوجد عميل</option>
            </select>
        </div>
    </div>


    <div class="col-md-2" id="customerDiv" @if($invoice_data['is_has_customer']==0 ) style="display: none;" @endif>
        <div class="form-group">
            <label> بيانات العملاء
                ( <a id="do_add_new_customer" title="إيضافة عميل جديد " href="#"> جديد <i class="fa fa-plus-circle"></i></a>)
            </label>
            <input type="text" class="form-control" id="searchbytextcustomer" placeholder="اسم العميل - كود العميل" value="{{$customer_name}}" />
            <div id="searchcustomerdiv">

            </div>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <label> بيانات المناديب</label>
            <select name="delegate_code" id="delegate_code" class="form-control select2 ">
                <option value=""> اختر المندوب</option>
                @if (@isset($delegates) && !@empty($delegates))
                @foreach ($delegates as $info )



                <option @if($invoice_data['delegate_code']==$info->delegate_code ) selected @endif
                    value="{{ $info->delegate_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>

    <div class="form-group col-md-2">
        <label> نوع الفاتروة </label>
        <select class="form-control" name="pill_type" id="pill_type">
            <option @if ($invoice_data['pill_type']==1) selected @endif value="1"> كاش</option>
            <option @if ($invoice_data['pill_type']==2) selected @endif value="2"> اجل</option>

        </select>

    </div>

</div>

<hr style="border: 1px solid #3c8dbc;">
<div class="row">


    <div class="col-md-3">
        <div class="form-group">
            <label> بيانات المخازن</label>
            <select name="store_id" id="store_id" class="form-control select2 ">
                <option value="">اختر المخزن </option>
                @if (@isset($stores) && !@empty($stores))
                @foreach ($stores as $info )
                <option value="{{ $info->id }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>



    <div class="col-md-3" id="customerDiv">
        <div class="form-group">
            <label> بيانات الاصناف</label>
            <input type="text" class="form-control" id="searchbytextitem_code" placeholder="اسم الصنف" />
            <div id="searchItemdiv">

            </div>
            @error('supplier_code')
            <span class="text-danger">{{$message}}</span>
            @enderror  
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> نوع البيع </label>
            <select class="form-control" name="sales_item_type" id="sales_item_type">
                <option value="1" selected>قطاعي </option>
                <option value="2">نص جملة</option>
                <option value="3"> جملة</option>
            </select>
        </div>
    </div>

    <div class="col-md-3" style="display: none;" id="UomDivAdd"></div>
    <div class="col-md-6 " style="display: none;" id="inv_itemcard_batchesDiv"></div>


    <div class="col-md-3">
        <div class="form-group ">
            <label> الكمية </label>
            <input value="1" oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" id="quantity" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group ">
            <label> السعر </label>
            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" id="price" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> هل البيع عادي </label>
            <select class="form-control" name="is_normal_orOthers" id="is_normal_orOthers">
                <option value="1" selected>عادي </option>
                <option value="2"> بونص</option>
                <option value="3"> دعاية</option>
                <option value="3"> هالك</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group ">
            <label> الإجمالي </label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" id="item_total" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group ">
            <button id="add_item_to_invoice_details" style="margin-top: 36px;" class="btn btn-sm btn-success">أضف
                للفاتورة</button>
        </div>
    </div>


</div>


<hr style="border: 1px solid #3c8dbc;">

<div class="row" id='active_items_salesDiv'>
    <h3 class="card-title card_title_center"> الأصناف المضافة على الفاتورة</h3>
    <table id="example2" class="table table-bordered table-hover" >
        <thead class="custom_thead" sty>
           
            <th>المخزن </th>
            <th> نوع البيع </th>
            <th> الصنف</th>
            <th> وحدة البيع </th>
            <th> سعر الوحدة </th>
            <th>الكمية </th>
            <th>الإجمالي </th>
            <th></th>
        </thead>

        <tbody id='itemsrowtableContainterBody'>
            @foreach ($items_sales_details as $info )
            <tr>
                <td>
                    {{ $info->store_name }}
                    <input type="hidden" name="item_total_array[]" class="item_total_array" value="{{$info->total_price}}">
                </td>
                <td>
                    @if ($info->sales_item_type == 1)
                    قطاعي
                    @elseif($info->sales_item_type == 2)
                    نص جملة
                    @else
                    جملة
                    @endif
                </td>
                <td>{{ $info->item_name }}</td>
                <td>{{ $info->uom_name }}</td>
                <td>{{ $info->unit_price*1 }}</td>
                <td>{{ $info->quantity }}</td>
                <td>{{ $info->total_price*1 }}</td>
                <td>
                    <button data-id="{{$info->id}}" class="btn remove_current_row btn-sm are_you_sure btn-danger">حذف</button>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
</div>

<hr style="border: 1px solid #3c8dbc;">

<div class="row">
    <div class="form-group col-lg-3">
        <label> إجمالي الاصناف </label>
        <input readonly type="text" name="total_cost_items" id="total_cost_items" class="form-control" value="{{$invoice_data['total_cost_items']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> نسبة الضريبة </label>
        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="tax_percent" id="tax_percent" class="form-control" value="{{$invoice_data['tax_percent']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> قيمة الضريبة </label>
        <input readonly type="text" name="tax_value" id="tax_value" class="form-control" value="{{$invoice_data['tax_value']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> الاجمالي قبل الخصم </label>
        <input readonly type="text" name="total_befor_discount" id="total_befor_discount" class="form-control" value="{{$invoice_data['total_befor_discount']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> نوع الخصم </label>
        <select class="form-control" id="discount_type" name="discount_type">
            <option value=""> لا يوجد خصم </option>
            <option @if ($invoice_data['discount_type']==1) selected @endif value="1"> نسبة مئوية</option>
            <option @if ($invoice_data['discount_type']==2) selected @endif value="2"> قيمة يدوي</option>

        </select>
    </div>
    <div class="form-group col-lg-3">
        <label> نسبة الخصم </label>
        <input @if($invoice_data['discount_type']==null ||$invoice_data['discount_type']=="" ) readonly @endif oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="discount_percent" id="discount_percent" class="form-control" value="{{$invoice_data['discount_percent']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> قيمة الخصم </label>
        <input readonly type="text" name="discount_value" id="discount_value" class="form-control" value="{{$invoice_data['discount_value']}}">
    </div>
    <div class="form-group col-lg-3">
        <label> الاجمالي النهائي </label>
        <input readonly type="text" name="total_cost" id="total_cost" class="form-control" value="{{$invoice_data['total_cost']}}">
    </div>
</div>
<div class="row">
    <div class="row col-lg-6" id="shift_div">
        <div class="form-group col-lg-6">
            <label> خزنة الصرف </label>
            <select id="treasures_id" class="form-control">
                @if(!@empty($user_shifts))
                <option selected value="{{$user_shifts['treasures_id']}}">{{ $user_shifts['treasures_name'] }}</option>
                @else
                <option value=""> عفوا لا خزنة لديك الان </option>
                @endif

            </select>
        </div>

        <div class="form-group col-lg-6">
            <label> الرصيد متاح للخزنة </label>
            <input readonly type="text" name="treasures_balance" id="treasures_balance" class="form-control" @if(!@empty($user_shifts)) value=" {{$user_shifts['current_blance']*1}} " @else value="0" @endif>
        </div>
    </div>
    <div class="row col-lg-6">
        <div class="form-group col-lg-6">
            <label> المحصل الان</label>
            <input @if($invoice_data['pill_type']==1) readonly @endif type="text" name="what_paid" id="what_paid" class="form-control" value="@if($invoice_data['pill_type'] == 1) {{$invoice_data['total_cost']}} @else 0 @endif">
        </div>
        <div class="form-group col-lg-6">
            <label> المتبقي نحصيله </label>
            <input readonly type="text" name="what_remain" id="what_remain" class="form-control" value="0">
        </div>
    </div>
    <div class="form-group col-lg-12">
        <label> الملاحظات </label>
        <input type="text" name="notes" id="notes" class="form-control" value=" {{$invoice_data['notes']}} ">
    </div>
    <div class="form-group col-lg-12 text-center">
        <hr style="border: 1px solid #3c8dbc;">

        <button type="submit" id="do_close_approve_invoice" style="padding: 10px;" class="btn btn-sm   btn-success"> اعتماد وترحيل </button>

    </div>
</div>
</div>
<script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@else

<div class="alert alert-danger">
    عفوا لايمكن التعديل على فاتورة معتمده وتم ترحيلها !!!!!
</div>
@endif