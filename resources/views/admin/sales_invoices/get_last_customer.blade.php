<div class="form-group">
    <label> بيانات العملاء
        ( <a title="إيضافة عميل جديد " href="#"> جديد <i class="fa fa-plus-circle"></i></a>)
    </label>
    <input type="text" class="form-control" id="searchbytextcustomer" placeholder="اسم العميل - كود العميل" />
    <div id="searchcustomerdiv">
        <select name="customer_code" id="customer_code_create" class="form-control select2 ">
            <option value=""> لا يوجد عميل </option>
            @if (@isset($customers) && !@empty($customers))
            @foreach ($customers as $info )
            <option selected value="{{ $info->customer_code }}">
                {{ $info->name }}
            </option>
            @endforeach
            @endif
        </select>
    </div>
</div>