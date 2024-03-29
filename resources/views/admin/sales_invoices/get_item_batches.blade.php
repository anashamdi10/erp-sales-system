@if (@isset($item_card_Data) && !@empty($item_card_Data))
<div class="form-group">
  <label> الكميات بالمخزن المحدد</label>
  <select id="inv_itemcard_batches_id" class="form-control " style="width: 100%;">
    @if (@isset($inv_itemcard_batches) && !@empty($inv_itemcard_batches) && count($inv_itemcard_batches)>0)
    <!-- لو كان مختار الوحده الاب يبقي الشغل علي وضعه لانه الكميات اساسا بالمخزن بالوحده الاب -->
    @if($uom_Data['is_master']==1)
    @foreach ( $inv_itemcard_batches as $info )
    @if($item_card_Data['item_type']==2)
    <!-- لو كان بتواريخ استهلاكي -->
    <option data-quantity='{{$info->quantity}}' value="{{ $info->auto_serial }}"> عدد {{ $info->quantity*(1) }} {{ $uom_Data['name'] }} انتاج {{ $info->production_date }} بتكلفة {{ $info->unit_cost_price*1 }} للوحدة </option>

    @else
    <option data-quantity='{{$info->quantity}}' value="{{ $info->auto_serial }}"> عدد {{ $info->quantity*(1) }} {{ $uom_Data['name'] }} بتكلفة {{ $info->unit_cost_price*1 }} للوحدة </option>

    @endif

    @endforeach

    @else
    <!-- لوكان مختار التجزئة يبقي لازن نحول الكميات الاب بالتجزئة -->

    @foreach ( $inv_itemcard_batches as $info )
    @php
    $quantity=$info->quantity*$item_card_Data['retail_uom_quantityToParent'];
    $unit_cost_price=$info->unit_cost_price/$item_card_Data['retail_uom_quantityToParent'];

    @endphp

    @if($item_card_Data['item_type']==2)
    //لو كان بتواريخ استهلاكي
    <option data-quantity='{{$quantity}}' value="{{ $info->auto_serial }}"> عدد {{ $quantity*(1) }} {{ $uom_Data['name'] }} انتاج {{ $info->production_date }} بتكلفة {{ $unit_cost_price*1 }} للوحدة </option>

    @else
    <option data-quantity='{{$quantity}}' value="{{ $info->auto_serial }}"> عدد {{ $quantity*(1) }} {{ $uom_Data['name'] }} بتكلفة {{ $unit_cost_price*1 }} للوحدة </option>

    @endif

    @endforeach



    @endif

    @endif
  </select>

</div>

@endif