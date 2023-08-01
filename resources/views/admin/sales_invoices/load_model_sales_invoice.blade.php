@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label> تاريخ الفاتورة </label>
            <input type="date" class="form-control" name="invoice_date_create" id="invoice_date_create" value="@php echo date('Y-m-d')@endphp">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label> فئات الفواتير</label>
            <select name="sales_material_type_create" id="sales_material_type_create" class="form-control select2 ">
                <option value=""> اختر فئة الفاتورة</option>
                @if (@isset($Sales_material_type) && !@empty($Sales_material_type))
                @foreach ($Sales_material_type as $info )
                <option value="{{ $info->id }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label> هل يوجد عميل </label>
            <select class="form-control" name="is_has_customer_create" id="is_has_customer_create">
                <option value="1" selected>نعم يوجد عميل</option>
                <option value="0">لا يوجد عميل</option>
            </select>
        </div>
    </div>
    <div class="col-md-2" id="customerDiv">
        <div class="form-group">
            <label> بيانات العملاء
                ( <a title="إيضافة عميل جديد " href="#"> جديد <i class="fa fa-plus-circle"></i></a>)
            </label>
            <select name="customer_code_create" id="customer_code_create" class="form-control select2 ">
                <option value=""> لا يوجد عميل </option>
                @if (@isset($customers) && !@empty($customers))
                @foreach ($customers as $info )
                <option value="{{ $info->customer_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label> بيانات المناديب</label>
            <select name="delgate_code_create" id="delgate_code_create" class="form-control select2 ">
                <option value=""> اختر المندوب</option>
                @if (@isset($delgates) && !@empty($delgates))
                @foreach ($delgates as $info )
                <option value="{{ $info->delegate_code }}"> {{ $info->name }} </option>
                @endforeach
                @endif
            </select>

        </div>
    </div>

    <div class="form-group col-md-2">
        <div class="form-group">
            <label> نوع الفاتروة </label>
            <select class="form-control" name="pill_type_create" id="pill_type">
                <option value="1"> كاش</option>
                <option  value="2"> اجل</option>

            </select>

        </div>

    </div>
</div>

    <hr style="border: 1px solid #3c8dbc;">
    <div class="form-group col-lg-12 text-center">
        <button type="submit" id="do_add_new_sales_invoice" style="padding: 10px;" class="btn btn-sm   btn-success"> اضف الفاتورة </button>
    </div>



    <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('admin/js/collect_tranaction.js')}}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>