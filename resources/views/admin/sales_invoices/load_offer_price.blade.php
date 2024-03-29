

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

    <tbody id='itemsrowtableContainterBody'>

    </tbody>

</table>

<hr style="border: 1px solid #3c8dbc;">


<div class="row">


    <div class="form-group col-lg-3">
        <label> إجمالي الاصناف </label>
        <input readonly type="text" name="total_cost_items" id="total_cost_items" class="form-control" value="">
    </div>
    <div class="form-group col-lg-3">
        <label> نسبة الضريبة </label>
        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="tax_percent" id="tax_percent" class="form-control" value="">
    </div>
    <div class="form-group col-lg-3">
        <label> قيمة الضريبة </label>
        <input readonly type="text" name="tax_value" id="tax_value" class="form-control" value="">
    </div>
    <div class="form-group col-lg-3">
        <label> الاجمالي قبل الخصم </label>
        <input readonly type="text" name="total_befor_discount" id="total_befor_discount" class="form-control" value="">
    </div>

    <div class="form-group col-lg-3">
        <label> نوع الخصم </label>
        <select class="form-control" id="discount_type" name="discount_type">
            <option value=""> لا يوجد خصم </option>
            <option value="1"> نسبة مئوية</option>
            <option value="2"> قيمة يدوي</option>

        </select>

    </div>


    <div class="form-group col-lg-3">
        <label> نسبة الخصم </label>
        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="discount_percent" id="discount_percent" class="form-control" value="">
    </div>
    <div class="form-group col-lg-3">
        <label> قيمة الخصم </label>
        <input readonly type="text" name="discount_value" id="discount_value" class="form-control" value="">
    </div>
    <div class="form-group col-lg-3">
        <label> الاجمالي النهائي </label>
        <input readonly type="text" name="total_cost" id="total_cost" class="form-control" value="">
    </div>
</div>
<div class="row" id="shift_div">
    <div class="form-group col-lg-3">
        <label> خزنة الصرف </label>
        <select id="treasures_id" class="form-control">
            @if(!@empty($user_shifts))
            <option selected value="{{$user_shifts['treasures_id']}}">{{ $user_shifts['treasures_name'] }}</option>
            @else
            <option value=""> عفوا لا خزنة لديك الان </option>
            @endif

        </select>
    </div>

    <div class="form-group col-lg-3">
        <label> الرصيد متاح للخزنة </label>
        <input readonly type="text" name="treasures_balance" id="treasures_balance" class="form-control" @if(!@empty($user_shifts)) value=" {{$user_shifts['current_blance']*1}} " @else value="0" @endif>
    </div>




    <div class="form-group col-lg-3">
        <label> نوع الفاتروة </label>
        <select class="form-control" name="pill_type" id="pill_type">
            <option value="1"> كاش</option>
            <option value="2"> اجل</option>

        </select>

    </div>


    <div class="form-group col-lg-3">
        <label>  المحصل  الان</label>
        <input type="text" name="what_paid" id="what_paid" class="form-control" value="0 ">
    </div>
    <div class="form-group col-lg-3">
        <label> المتبقي نحصيله </label>
        <input readonly type="text" name="what_remain" id="what_remain" class="form-control" value="0">
    </div>
    <div class="form-group col-lg-9">
        <label> الملاحظات </label>
        <input type="text" name="notes" id="notes" class="form-control" value="0">
    </div>
    <div class="form-group col-lg-12 text-center">
    <hr style="border: 1px solid #3c8dbc;">

        <button type="submit" id="do_close_approve_invoice" style="padding: 10px;" class="btn btn-sm   btn-success">  طباعه عرض الاسعار </button>

    </div>

</div>