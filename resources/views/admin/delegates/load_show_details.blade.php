<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label> اسم المندوب </label>
            <input readonly type="text" name="name" id="name" class="form-control" value="{{old('name',$data['name'])}}" style="margin-top: 24px;">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> نوع العمولة </label>
            <select disabled class="form-control" name="percent_type" id="percent_type" style="margin-top: 24px;">
                <option value="" selected="selected">اختر الحاله </option>
                <option {{ $data['percent_type']==1? 'selected':'' }} value="1">اجر ثابت </option>
                <option {{ $data['percent_type']==2? 'selected':'' }} value="2">نسبة </option>
            </select>
            
        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group">
            <label> نسبة العمولة المندوب بالمبيعات قطاعي </label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_kataei" id="percent_sales_commission_kataei" class="form-control" value="{{old('percent_sales_commission_kataei',$data['percent_sales_commission_kataei'])}}">
            
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> نسبة العمولة المندوب بالمبيعات نص جملة</label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_nosjomla" id="percent_sales_commission_nosjomla" class="form-control" value="{{old('percent_sales_commission_kataei',$data['percent_sales_commission_kataei'])}}">
            
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> نسبة العمولة المندوب بالمبيعات جملة </label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_sales_commission_jomla" id="percent_sales_commission_jomla" class="form-control" value="{{old('percent_sales_commission_jomla',$data['percent_sales_commission_jomla'])}}">
            
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> نسبة المندوب بالتحصيل الفواتير اجل </label>
            <input readonly oninput="this.value=this.value.replace(/[^0-9.]/g,'');" name="percent_collect_commiission" id="percent_collect_commiission" class="form-control" value="{{old('percent_collect_commiission   ',$data['percent_collect_commiission'])}}">
    
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label> العنوان </label>
            <input readonly type="text" name="address" id="address" class="form-control" value="{{old('address',$data['address'])}}">

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> الجوال</label>
            <input readonly type="text" name="phones" id="phones" class="form-control" value="{{old('phones',$data['phones'])}}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> ملاحظات </label>
            <input readonly type="text" name="notes" id="notes" class="form-control" value="{{old('notes',$data['notes'])}}">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label> حاله التفعيل</label>
            <select disabled class="form-control" name="active" id="active">
                <option value="" selected="selected">اختر الحاله </option>
                <option {{ old('active',$data['active'])==1? 'selected':'' }} value="1">نعم </option>
                <option {{ old('active',$data['active'])==0? 'selected':'' }} value="0">لا </option>
            </select>
        
        </div>
    </div>
</div>