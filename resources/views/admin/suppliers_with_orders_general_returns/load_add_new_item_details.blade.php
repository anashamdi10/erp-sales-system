<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label> بيانات المخازن</label>
            <select name="store_id" id="store_id" class="form-control select2 ">

                @if (@isset($stors) && !@empty($stors))
                @foreach ($stors as $info )
                <option value="{{ $info->id }}"> {{ $info->name }} </option>
                @endforeach
                @else
                <option value="">اختر المخزن</option>
                @endif
            </select>
            @error('supplier_code')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label> بيانات الاصناف</label>
            <select name="item_code_return" id="item_code_return" class="form-control select2 ">
                <option value="">اختر الصنف</option>
                @if (@isset($items_cards) && !@empty($items_cards))
                @foreach ($items_cards as $info )
                <option data-type="{{$info->item_type}}" value="{{ $info->item_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>
            @error('supplier_code')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4" style="display: none;" id="UomDivAdd"></div>
    <div class="col-md-8" style="display: none;" id="inv_itemcard_batchesDiv"></div>

    <div class="col-md-4 related_to_itemCard" style="display: none;">
        <div class="form-group ">
            <label> الكمية المستلمة </label>
            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" id="quantity_add" class="form-control">

        </div>

    </div>
    <div class="col-md-4 related_to_itemCard" style="display: none;">
        <div class="form-group ">
            <label> سعر الوحدة </label>
            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" id="price_add" class="form-control">

        </div>

    </div>
    <div class="col-md-4 related_to_date" style="display: none;">
        <div class="form-group ">
            <label> تاريخ الانتاج </label>
            <input type="date" id="production_date" class="form-control">

        </div>

    </div>
    <div class="col-md-4 related_to_date" style="display: none;">
        <div class="form-group ">
            <label> تاريخ انتهاء الصلاحية </label>
            <input type="date" id="expire_date" class="form-control">

        </div>

    </div>
    <div class="col-md-4 related_to_itemCard" style="display: none;">
        <div class="form-group ">
            <label> الاجمالي </label>
            <input readonly type="text" id="total_add" class="form-control">

        </div>

    </div>

    <div class="col-md-12">
        <div class="form-group text-center">
            <button type="button" class="btn btn-info" id="AddToBill">أضف للفاتورة </button>
        </div>
    </div>





</div>