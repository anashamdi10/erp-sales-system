<select name="item_code" id="item_code" class="form-control select2 ">
    <option value="" disabled>اختر الصنف</option>
    @if (@isset($items_card) && !@empty($items_card))
    @foreach ($items_card as $info )
    <option selected data-item-type="{{$info->item_type}}" value="{{ $info->item_code }}"> {{ $info->name }}
    </option>
    @endforeach
    @endif
</select>