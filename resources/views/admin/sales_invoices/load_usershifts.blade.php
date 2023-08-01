<div class="form-group col-lg-6">
    <label> خزنة الصرف </label>
    <select id="treasures_id" class="form-control">
        @if(!@empty($user_shifts))
        <option selected value="{{$user_shifts['treasures_id']}}">{{ $user_shifts['treasures_name'] }}</option>
        @else
        <option value=""> عفوا لا خزنة لديك الان </option>
        @endif

    </select>
</div>

<div class="form-group col-lg-6">
    <label> الرصيد متاح للخزنة </label>
    <input readonly type="text" name="treasures_balance" id="treasures_balance"
        class="form-control" @if(!@empty($user_shifts)) value=" {{$user_shifts['current_blance']*1}} " @else value="0" @endif>
</div>