<select name="customer_code" id="customer_code_create" class="form-control select2 ">
    @if (@isset($customers) && !@empty($customers))
    @foreach ($customers as $info )
    <option selected value="{{ $info->customer_code }}">
        {{ $info->name }}
    </option>
    @endforeach
    @endif
</select>