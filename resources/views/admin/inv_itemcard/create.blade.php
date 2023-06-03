@extends('layouts.admin')
@section('title')
اضافة صنف
@endsection
@section('contentheader')
الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('inv_itemcard.index') }}"> الاصناف </a>
@endsection


@section('contentheaderactive')
إضافة
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> إضافة صنف جديد </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{route('inv_itemcard.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> (في حالة عدم الاستخدام سيولد بشكل الي) باركود الصنف</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" value="{{old('barcode')}}" placeholder="ادخل  باركود الصنف  ">
                        @error('barcode')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم الصنف</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" placeholder="ادخل  اسم الصنف  ">
                        @error('name')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> نوع الصنف</label>
                        <select class="form-control" name="item_type" id="item_type">
                            <option value="" selected="selected">اختر النوع </option>
                            <option @if(old('item_type')==1) selected="selected" @endif value="1">مخزني </option>
                            <option @if(old('item_type')==2) selected="selected" @endif value="2">استهلاكي بتاريخ صلاحية </option>
                            <option @if(old('item_type')==3) selected="selected" @endif value="3">عهده </option>

                        </select>
                        @error('item_type')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> فئة الصنف</label>
                        <select name="inv_itemcard_categories_id" id="inv_itemcard_categories_id" class="form-control ">
                            <option value="">اختر الفئة</option>
                            @if (@isset($inv_itemcard_categories) && !@empty($inv_itemcard_categories))
                                @foreach ($inv_itemcard_categories as $info )
                                    <option @if(old('inv_itemcard_categories_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('inv_itemcard_categories_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>  الصنف اب له</label>
                        <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control ">
                            <option value="0" selected> هو اب</option>
                            @if (@isset($parent_inv_itemcard_id) && !@empty($parent_inv_itemcard_id))
                                @foreach ($parent_inv_itemcard_id as $info )
                                    <option @if(old('parent_inv_itemcard_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('parent_inv_itemcard_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>  وحده قياس اب</label>
                        <select name="uom_id" id="uom_id" class="form-control ">
                            <option value=""> اختر الوحدة اب</option>
                            @if (@isset($Inv_ums_parent) && !@empty($Inv_ums_parent))
                                @foreach ($Inv_ums_parent as $info )
                                    <option @if(old('Inv_ums_parent')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('uom_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label> هل للصتف وحده تحزئة ابن</label>
                        <select class="form-control" name="does_has_retailunit" id="does_has_retailunit">
                            <option selected="selected" value="">اختر الحالة </option>
                            <option @if(old('does_has_retailunit')==1) selected="selected" @endif value="1">نعم </option>
                            <option @if(old('does_has_retailunit')==0 and old('does_has_retailunit'!='' )) selected="selected" @endif value="0">لا </option>
                            

                        </select>
                        @error('does_has_retailunit')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6" @if(old('does_has_retailunit')!=1) style="display: none;" @endif id="retail_uom_idDiv">
                    <div class="form-group">
                        <label>  وحده قياس التجزئةالابن بالنسبة للاب (<span class="parentuomname"></span>)</label>
                        <select name="retail_uom_id" id="retail_uom_id" class="form-control ">
                            <option value=""> اختر الوحدة اب</option>
                            @if (@isset($Inv_ums_child) && !@empty($Inv_ums_child))
                                @foreach ($Inv_ums_child as $info )
                                    <option @if(old('retail_uom_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('retail_uom_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter"  @if(old('retail_uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label> عدد وحدات التجزئة (<span class="childuomname"></span>) بالنسبة للاب (<span class="parentuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="retail_uom_quantityToParent"
                                id="retail_uom_quantityToParent" class="form-control"  placeholder="ادخل عدد وحدات التجزئة" 
                                value="{{old('retail_uom_quantityToParent')}}">
                                @error('retail_uom_quantityToParent')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>
                <div class="col-md-6 retailed_parent_counter"  @if(old('uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>     السعر القطاعي بوحدة  (<span class="parentuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="price"
                                id="price" class="form-control"  value="{{old('price')}}">
                                @error('price')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>

                <div class="col-md-6 retailed_parent_counter"  @if(old('uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>  سعر نص جملة بالوحده  (<span class="parentuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="nos_gomla_price"
                                id="nos_gomla_price" class="form-control"  value="{{old('nos_gomla_price')}}">
                                @error('nos_gomla_price')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>

                <div class="col-md-6 retailed_parent_counter"  @if(old('uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>  سعر  جملة بالوحده  (<span class="parentuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="gomla_price"
                                id="gomla_price" class="form-control"  value="{{old('gomla_price')}}">
                                @error('gomla_price')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>
                <div class="col-md-6 retailed_parent_counter"  @if(old('uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>  سعر  تكلفة الشراء لوحده  (<span class="parentuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="cost_price"
                                id="cost_price" class="form-control"  value="{{old('cost_price')}}">
                                @error('cost_price')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>

                <div class="col-md-6 retailed_retail_counter" @if(old('retail_uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>     السعر القطاعي بوحدة  (<span class="childuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="price_retail"
                                id="price_retail" class="form-control"  value="{{old('price_retail')}}">
                                @error('price_retail')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('retail_uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>     سعر النص جملة بوحدة  (<span class="childuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="nos_gomla_price_retail"
                                id="nos_gomla_price_retail" class="form-control"  value="{{old('nos_gomla_price_retail')}}">
                                @error('nos_gomla_price_retail')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('retail_uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>     سعر  جملة بوحدة  (<span class="childuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="gomla_price_retail"
                                id="gomla_price_retail" class="form-control"  value="{{old('gomla_price_retail')}}">
                                @error('gomla_price_retail')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('retail_uom_id')=="") style="display: none;" @endif>
                    <div class="form-group">
                            <label>     سعر  شراء بوحدة  (<span class="childuomname"></span>)</label>
                            <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="cost_price_retail"
                                id="cost_price_retail" class="form-control"  value="{{old('cost_price_retail')}}">
                                @error('cost_price_retail')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror    
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> هل للصنف سعر ثابت </label>
                        <select class="form-control" name="has_fixed_price" id="has_fixed_price">
                            <option value="" selected="selected">اختر الحاله </option>
                            <option @if(old('has_fixed_price')==1) selected="selected" @endif value="1">نعم </option>
                            <option @if(old('has_fixed_price')==0 and old('has_fixed_price'!='' )) selected="selected" @endif value="0">لا </option>
                        </select>
                        @error('has_fixed_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> حاله التفعيل</label>
                        <select class="form-control" name="active" id="active">
                            <option value="" selected="selected">اختر الحاله </option>
                            <option @if(old('active')==1) selected="selected" @endif value="1">نعم </option>
                            <option @if(old('active')==0 and old('active'!='' )) selected="selected" @endif value="0">لا </option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" style="border: solid 5px #000; margin: 10px;">
                    <div class="form-group">
                        <label>  صورة الصنف ان وجدت</label>
                        <img id="uploadedimg" src="#" alt="uploaded img" style="width: 200px; height: 200px;">
                        <input type="file" id="Item_image" name="Item_image" class="form-control" onchange="readURL(this)" >
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group text-center" style="margin-bottom: 50px;">
                <button id="to_add_item_card" type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                <a href="{{route('admin.stores.index')}}" class="btn btn-sm btn-danger">الغاء</a>
            </div>

        </form>
    </div>
</div>
</div>

@endsection



@section('script')
<script src="{{asset('admin/js/inv_itemcard.js')}}"></script>

<script>
    var uom_id = $('#uom_id').val();
    if(uom_id !=""){
        var name=$('#uom_id option:select').text();
        $('.parentuomname').text(name);
    }
    var retail_uom_id = $('#retail_uom_id').val();
    if(retail_uom_id !=""){
        var name = $('#retail_uom_id option:selected').text();
        $('.childuomname').text(name);
    }
</script>


@endsection