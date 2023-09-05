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
            <label> مخزن صرف المرتجع</label>
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
            <label> إجمالي الاصناف على الفاتورة </label>
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
            <label> نسبة القيمة المضافة </label>
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
            <label> إجمالي الفاتورة </label>
        </div>
        <div class="input-group-prepend" style="width: 100%">
            <span class="input-group-text">
                <i class="fa-solid fa-money-bill"></i>
            </span>
            <div class="form-control"> {{ $data['total_cost']*1 }}</div>
        </div>
    </div>
    <div class="form-group mb-3 col-lg-3">
        <div>
            <label> حالة الفاتورة </label>
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