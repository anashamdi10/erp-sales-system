@extends('layouts.admin')
@section('title')
تعديل صنف
@endsection
@section('contentheader')
الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('inv_itemcard.index') }}"> الاصناف </a>
@endsection


@section('contentheaderactive')
تعديل
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> تعديل بيانات صنف </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{route('inv_itemcard.update',$data['id'])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label> (في حالة عدم الاستخدام سيولد بشكل الي) باركود الصنف</label>
                        <input type="text" name="barcode" id="barcode" class="form-control" value="{{old('barcode', $data['barcode'])}}" placeholder="ادخل  باركود الصنف  ">
                        @error('barcode')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> اسم الصنف</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name', $data['name'])}}" placeholder="ادخل  اسم الصنف  ">
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
                            <option {{ old('item_type',$data['item_type'])==1? 'selected':''}} value="1">مخزني </option>
                            <option {{ old('item_type',$data['item_type'])==2? 'selected':''}} value="2">استهلاكي بتاريخ صلاحية </option>
                            <option {{ old('item_type',$data['item_type'])==3? 'selected':''}} value="3">عهده </option>

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
                            <option {{ old('inv_itemcard_categories_id',$data['inv_itemcard_categories_id'])== $info->id ? 'selected':''}} value="{{ $info->id }}"> {{ $info->name }} </option>
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
                        <label> الصنف اب له</label>
                        <select name="parent_inv_itemcard_id" id="parent_inv_itemcard_id" class="form-control ">
                            <option value="0" selected> هو اب</option>
                            @if (@isset($parent_inv_itemcard_id) && !@empty($parent_inv_itemcard_id))
                            @foreach ($parent_inv_itemcard_id as $info )
                            <option {{ old('parent_inv_itemcard_id',$data['parent_inv_itemcard_id'])== $info->id ? 'selected':''}} value="{{ $info->id }}"> {{ $info->name }} </option>
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
                        <label> وحده قياس اب</label>
                        <select name="uom_id" id="uom_id" class="form-control ">
                            <option value=""> اختر الوحدة اب</option>
                            @if (@isset($Inv_ums_parent) && !@empty($Inv_ums_parent))
                            @foreach ($Inv_ums_parent as $info )
                            <option {{  old('uom_id',$data['uom_id'])==$info->id ? 'selected' : ''}} value="{{ $info->id }}"> {{ $info->name }} </option> @endforeach
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
                            <option {{ old('does_has_retailunit',$data['does_has_retailunit'])==1? 'selected':'' }} value="1">نعم </option>
                            <option {{ old('does_has_retailunit',$data['does_has_retailunit'])==0? 'selected':'' }} value="0">لا </option>


                        </select>
                        @error('does_has_retailunit')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>



                <div class="col-md-6" id="retail_uom_idDiv">
                    <div class="form-group">
                        <label> وحدة القياس التجزئة الابن بالنسبة للأب(<span class="parentuomname"></span>)</label>
                        <select name="retail_uom_id" id="retail_uom_id" class="form-control ">
                            <option value=""> اختر الوحدة اب</option>
                            @if (@isset($Inv_ums_child) && !@empty($Inv_ums_child))
                            @foreach ($Inv_ums_child as $info )
                            <option {{ old('retail_uom_id',$data['retail_uom_id'])== $info->id ? 'selected':''}} value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('retail_uom_id')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> عدد وحدات التجزئة (<span class="childuomname"></span>) بالنسبة للاب (<span class="parentuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="retail_uom_quantityToParent" id="retail_uom_quantityToParent" class="form-control" placeholder="ادخل عدد وحدات التجزئة" value="{{old('retail_uom_quantityToParent', $data['retail_uom_quantityToParent'])}}">
                        @error('retail_uom_quantityToParent')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_parent_counter">
                    <div class="form-group">
                        <label> السعر القطاعي بوحدة (<span class="parentuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="price" id="price" class="form-control" value="{{old('price',$data['price'])}}">
                        @error('price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 retailed_parent_counter">
                    <div class="form-group">
                        <label> سعر نص جملة بالوحده (<span class="parentuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="nos_gomla_price" id="nos_gomla_price" class="form-control" value="{{old('nos_gomla_price',$data['nos_gomla_price'])}}">
                        @error('nos_gomla_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 retailed_parent_counter">
                    <div class="form-group">
                        <label> سعر جملة بالوحده (<span class="parentuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="gomla_price" id="gomla_price" class="form-control" value="{{old('gomla_price', $data['gomla_price'])}}">
                        @error('gomla_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_parent_counter">
                    <div class="form-group">
                        <label> سعر تكلفة الشراء لوحده (<span class="parentuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="cost_price" id="cost_price" class="form-control" value="{{old('cost_price',$data['cost_price'])}}">
                        @error('cost_price')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 retailed_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> السعر القطاعي بوحدة (<span class="childuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="price_retail" id="price_retail" class="form-control" value="{{old('price_retail',$data['price_retail'])}}">
                        @error('price_retail')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> سعر النص جملة بوحدة (<span class="childuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="nos_gomla_price_retail" id="nos_gomla_price_retail" class="form-control" value="{{old('nos_gomla_price_retail',$data['nos_gomla_price_retail'])}}">
                        @error('nos_gomla_price_retail')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> سعر جملة بوحدة (<span class="childuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="gomla_price_retail" id="gomla_price_retail" class="form-control" value="{{old('gomla_price_retail',$data['gomla_price_retail'])}}">
                        @error('gomla_price_retail')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 retailed_retail_counter" @if(old('does_has_retailunit',$data['does_has_retailunit'])!=1 ) style="display: none;" @endif>
                    <div class="form-group">
                        <label> سعر شراء بوحدة (<span class="childuomname"></span>)</label>
                        <input oninput="this.value=this.value.replace(/[^0-9.]/g,'');" type="text" name="cost_price_retail" id="cost_price_retail" class="form-control" value="{{old('cost_price_retail',$data['cost_price_retail'])}}">
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
                            <option {{ old('has_fixed_price',$data['has_fixed_price'])==1? 'selected':'' }} value="1">نعم </option>
                            <option {{ old('has_fixed_price',$data['has_fixed_price'])==0? 'selected':'' }} value="0">لا </option>
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
                            <option {{ old('active',$data['active'])==1? 'selected':'' }} value="1">نعم </option>
                            <option {{ old('active',$data['active'])==0? 'selected':'' }} value="0">لا </option>
                        </select>
                        @error('active')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6" style="border: solid 5px #000; margin: 10px;">
                    <div class="form-group">
                        <label> صورة الصنف</label>
                        <div class="image">
                            <img id="uploadedimg" class="custom_img" src="{{ asset('admin/uploads').'/'.$data['photo'] }}" alt="لوجو الصنف">
                            <button type="button" class="btn btn-sm btn-danger" id="update_image">تغير الصورة</button>
                            <button type="button" class="btn btn-sm btn-danger" style="display: none;" id="cancel_update_image"> الغاء</button>
                        </div>
                        <div id="oldimage">
                        </div>
                    </div>
                </div>
                <div class="form-group text-center" style="margin-bottom: 50px;">
                    <button id="do_edit_item_card" type="submit" class="btn btn-primary btn-sm">حفظ تعديلات </button>
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
    if (uom_id != "") {
        var name = $('#uom_id option:select').text();
        $('.parentuomname').text(name);
    }
    var retail_uom_id = $('#retail_uom_id').val();
    if (retail_uom_id != "") {
        var name = $('#retail_uom_id option:selected').text();
        $('.childuomname').text(name);
    }
</script>


@endsection