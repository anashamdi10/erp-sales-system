@if(!@empty($parent_pill_data))

    @if($parent_pill_data['is_approved']==0)
        @if(!@empty($item_data_details))

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label> بيانات الاصناف</label>
                        <select name="item_code_edit" id="item_code_edit" class="form-control select2 ">
                            
                            @if (@isset($items_cards) && !@empty($items_cards))
                            @foreach ($items_cards as $info )
                            <option @if($item_data_details['item_code']==$info->item_code) selected= "selected" @endif 
                                 data-type="{{$info->item_type}}" value="{{ $info->item_code }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('supplier_code')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4  related_to_itemCard" id="UomDivAdd">
                    <div class="form-group">
                        <label> بيانات  وحدات الصنف</label>
                            <select name="uom_id_edit" id="uom_id_edit" class="form-control select2 ">
                                
                                @if (@isset($item_card_data) && !@empty($item_card_data))
                                    @if($item_card_data['does_has_retailunit']==1)

                                        <option  @if($item_card_data['uom_id'] == $item_data_details['uom_id']) selected @endif   data-isparentuom = '1' value="{{ $item_card_data['uom_id'] }}"> {{ $item_card_data['parent_uom_name'] }} (وحده اب) </option>
                                        <option  @if($item_card_data['retail_uom_name'] == $item_data_details['uom_id']) selected @endif data-isparentuom = '0' value="{{ $item_card_data['retail_uom_id'] }}"> {{ $item_card_data['retail_uom_name'] }}  (وحده تجزئة) </option>

                                    @else 
                                        <option selected data-isparentuom = '1' value="{{ $item_card_data['uom_id'] }}"> {{ $item_card_data['parent_uom_name'] }} </option>

                                    @endif
                                        
                                @endif
                            </select>
                    </div>
                </div>

                <div class="col-md-4 related_to_itemCard" >
                    <div class="form-group ">
                        <label> الكمية المستلمة </label>
                        <input value="{{ $item_data_details['dliverd_quantity']}}" oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" id="quantity_edit" class="form-control">

                    </div>

                </div>
                <div class="col-md-4 related_to_itemCard" >
                    <div class="form-group ">
                        <label> سعر الوحدة </label>
                        <input  value="{{ $item_data_details['unit_price']*1}}" oninput="this.value=this.value.replace(/[^0-9]/g,'');" type="text" id="price_edit" class="form-control">

                    </div>

                </div>
                <div class="col-md-4 related_to_date" @if($item_data_details['item_card_type']!=2) style="display: none;" @endif>
                    <div class="form-group ">
                        <label> تاريخ الانتاج </label>
                        <input type="date" id="production_date_edit" class="form-control" value="{{ $item_data_details['production_date']}}">

                    </div>

                </div>
                <div class="col-md-4 related_to_date" @if($item_data_details['item_card_type']!=2) style="display: none;" @endif>
                    <div class="form-group ">
                        <label> تاريخ انتهاء الصلاحية </label>
                        <input type="date" id="expire_date_edit" class="form-control" value="{{ $item_data_details['expire_date']}}">

                    </div>

                </div>
                <div class="col-md-4 related_to_itemCard" >
                    <div class="form-group ">
                        <label> الاحمالي </label>
                        <input value="{{ $item_data_details['total_price']}}" readonly type="text" id="total_edit" class="form-control">

                    </div>

                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button  data-id = "{{$item_data_details['id']}}" type="button" class="btn btn-info" id="EditDetailsitem">تعديل الصتف</button>
                    </div>
                </div>





            </div>
        
       
        @endif

    
    @endif


@endif