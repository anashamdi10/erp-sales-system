<div class="form-group">
    <label> رصيد الحساب </label>
    <input readonly name="the_current_balance" id="the_current_balance" style="color: brown;" class="form-control" @if($the_current_balance> 0 )
    value ='مدين ب ({{ $the_current_balance *1 }}) جنيه'
    @elseif($the_current_balance < 0) value=' دائن ب ({{ $the_current_balance *(-1) }}) جنيه ' @else value='متزن' @endif>
</div>