<select name="customer_code" id="customer_code_create" class="form-control select2 ">
    <option value="100">اختر</option>
    @if (@isset($customers) && !@empty($customers))
    @foreach ($customers as $info )
    <option  value="{{ $info->customer_code }}">
        {{ $info->name }}
    </option>
    @endforeach
    @endif
</select>