<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label> تاريخ الفاتورة </label>
            <input type="date" class="form-control" name="invoice_date" value="@php echo date('Y-m-d')@endphp">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> هل يوجد عميل </label>
            <select class="form-control" name="is_has_customer" id="is_has_customer">
                <option value="1" selected>نعم يوجد عميل</option>
                <option value="0">لا يوجد عميل</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> بيانات العملاء
                ( <a title="إيضافة عميل جديد " href="#"> جديد <i class="fa fa-plus-circle"></i></a>)
            </label>
            <select name="customer_code" id="customer_code" class="form-control select2 ">
                <option value=""> لا يوجد عميل </option>
                @if (@isset($customers) && !@empty($customers))
                @foreach ($customers as $info )
                <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> بيانات المناديب</label>
            <select name="customer_code" id="supplier_code" class="form-control select2 ">
                <option value=""> اختر المندوب</option>
                @if (@isset($customers) && !@empty($customers))
                @foreach ($customers as $info )
                <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>
</div>

<hr style="border: 1px solid #3c8dbc;">

<div class="row">


    <div class="col-md-3">
        <div class="form-group">
            <label> بيانات المخازن</label>
            <select name="store_id" id="store_id" class="form-control select2 ">
                <option value="">اختر المخزن  </option>
                @if (@isset($stores) && !@empty($stores))
                @foreach ($stores as $info )
                <option value="{{ $info->id }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>
           
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
    <div class="col-md-3">
        <div class="form-group">
            <label> بيانات الاصناف</label>
            <select name="item_code" id="item_code" class="form-control select2 ">
                <option value="">اختر الصنف</option>
                @if (@isset($items_cards) && !@empty($items_cards))
                @foreach ($items_cards as $info )
                <option data-item-type="{{$info->item_type}}" value="{{ $info->item_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>
            @error('supplier_code')
            <span class="text-danger">{{$message}}</span>
            @enderror
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
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" id="total_cost" class="form-control">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group ">
            <button id="add_item" style="margin-top: 36px;" class="btn btn-sm btn-danger">أضف للفاتورة</button>
        </div>
    </div>


</div>
<hr style="border: 1px solid #3c8dbc;">
<h3 class="card-title card_title_center"> الأصناف المضافة على الفاتورة</h3>
<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">
        <th>المخزن </th>
        <th> نوع البيع </th>
        <th> الصنف</th>
        <th> وحدة البيع </th>
        <th> سعر الوحدة </th>
        <th>الكمية </th>

        <th>الإجمالي </th>
        <th></th>

    </thead>

    <tbody id ='itemsrowtableContainterBody'>

    </tbody>

</table>