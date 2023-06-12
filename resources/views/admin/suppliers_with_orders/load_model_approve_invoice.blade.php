@if(@isset($data)&& !@empty($data))

    @if($data['is_approved'] == 0)
    <div class="row">

   
        <div class="form-group col-lg-3">
            <label> إجمالي الاصناف بالفاتورة </label>
            <input readonly type="text" name="total_cost_items" id="total_cost_items"class="form-control" value="{{$data['total_cost_items']}}">
        </div>
        <div class="form-group col-lg-3">
            <label> نسبة ضريبة القيمة المضافة  </label>
            <input  oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="tax_percent" id="tax_percent"
                    class="form-control" value="{{$data['tax_percent']*1}}">
        </div>
        <div class="form-group col-lg-3">
            <label>   القيمة الضريبة المضافة  </label>
            <input readonly  type="text" name="tax_value" id="tax_value"
                    class="form-control" value="{{$data['tax_value']*1}}">
        </div>
        <div class="form-group col-lg-3">
            <label>   القيمة الاجمالي  قبل الخصم   </label>
            <input readonly  type="text" name="total_befor_discount" id="total_befor_discount"class="form-control" value="{{$data['total_befor_discount']*1}}">
        </div>

        <div class="form-group col-lg-3">
            <label> نوع الخصم ان وجد</label>
            <select class="form-control" id="discount_type">
                <option value=""> لا يوجد خصم </option>
                <option value="1" @if($data["discount_type"]==1) selected @endif> نسبة مئوية</option>
                <option value="2" @if($data["discount_type"]==2) selected @endif>  قيمة يدوي</option>

            </select>

        </div>


        <div class="form-group col-lg-3">
            <label>   نسبة الخصم   </label>
            <input  oninput="this.value=this.value.replace(/[^0-9.]/g,'');"  type="text" name="discount_percent" 
                    @if($data["discount_type"]==''||$data["discount_type"]==null ) readonly @endif
                    id="discount_percent"class="form-control" value="{{$data['discount_percent']*1}}">
        </div>
        <div class="form-group col-lg-3">
            <label>   قيمة  الخصم   </label>
            <input readonly  type="text" name="discount_value" id="discount_value"class="form-control" value="{{$data['discount_value']*1}}">
        </div>
        <div class="form-group col-lg-3">
            <label>   الاجمالي بعد الخصم     </label>
            <input readonly  type="text" name="total_cost" id="total_cost"class="form-control" value="{{$data['total_cost']*1}}">
        </div>
    </div>
    <div class="row" id="shift_div">
        <div class="form-group col-lg-6">
            <label> خزنة الصرف      </label>
            <select  id="treasures_id" class="form-control">
                @if(!@empty($user_shifts))
                    <option selected     value="{{$user_shifts['treasures_id']}}">{{ $user_shifts['treasures_name'] }}</option>
                @else
                    <option  value=""> عفوا لا خزنة لديك الان </option>
                @endif

            </select>
        </div>

        <div class="form-group col-lg-6">
            <label> الرصيد متاح للخزنة </label>
            <input readonly type="text" name="treasures_balance" id="treasures_balance" class="form-control" 
            @if(!@empty($user_shifts))
                value=" {{$user_shifts['current_blance']*1}} "
            @else
                value = "0"

            @endif
            
            >
        </div>

    </div>

    <div class="row">

        <div class="form-group col-lg-3">
            <label> نوع الفاتروة   </label>
            <select class="form-control" id="pill_type">
                <option value="1" @if($data["pill_type"]==1) selected @endif>  كاش</option>
                <option value="2" @if($data["pill_type"]==2) selected @endif>   اجل</option>

            </select>

        </div>


        <div class="form-group col-lg-3">
            <label>  المدفوع للمورد الان</label>
            <input   type="text" name="what_paid" id="what_paid"class="form-control"  @if($data["pill_type"]==1) readonly 
                     value=" {{$data['total_cost']*1}} "  @else   value="0 " @endif > 
        </div>
        <div class="form-group col-lg-3">
            <label>  المتبقي  للمورد </label>
            <input  readonly  type="text" name="what_remain" id="what_remain"class="form-control"  @if($data["pill_type"]==1)  
                     value="0"  @else   value=" {{$data['total_cost']*1}} " @endif > 
        </div>
        <div class="form-group col-lg-12 text-center">
            <hr>    
            <button id="do_close_approve_invoice"  style="padding: 10px;" class="btn btn-sm   btn-danger">  اعتماد والترحيل الان</button>

        </div>

    </div>    





    </div>    





    @else

    <div class="alert alert-danger">
        عفوا لقد تم اعتماد الفاتورة من قبل  !!
    </div>
@endif

@else

    <div class="alert alert-danger">
        عفوا لاتوجد بيانات لعرضها !!
    </div>
@endif