@extends('layouts.admin')
@section('title')
عرض صنف
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection


@section('contentheader')
الاصناف
@endsection
@section('contentheaderlink')
<a href="{{ route('inv_itemcard.index') }}"> الاصناف </a>
@endsection




@section('contentheaderactive')
عرض
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">بيانات الضبط العام </h3>
        <input type="hidden" id="token_search" value="{{csrf_token() }}">
        <input type="hidden" id="ajax_search_url_show" value="{{ route('admin.inv_itemcard.ajax_search_show') }}">
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <table id="example2" class="table table-bordered table-hover">

            <tr>
                <td colspan="3">
                    <label>كود الصنف الثابت الالي من النظام</label> <br>
                    {{$data['item_code']}}
                </td>
            </tr>

            <tr>
                <td>
                    <label> باركود الصنف </label> <br>
                    {{ $data['barcode']}}
                </td>
                <td>
                    <label> اسم الصنف </label> <br>
                    {{ $data['name']}}
                </td>

                <td>
                    <label> نوع الصنف</label> <br>
                    @if($data['item_type'] ==1) مخزني @elseif($data['item_type']==2) استهلاكي @elseif($data['item_type']==3) عهده @endif
                </td>

            </tr>

            <tr>
                <td>
                    <label> نوع الصنف</label> <br>
                    {{ $data['inv_itemcard_categories_name']}}
                </td>
                <td>
                    <label> الصنف الاب</label> <br>
                    {{ $data['parent_item_name']}}
                </td>
                <td>
                    <label> وحده قياس الاب</label> <br>
                    {{ $data['uom_name']}}
                </td>

            </tr>
            <tr>
                <td @if( $data[ 'does_has_retailunit' ]==0 ) colspan="3" @endif>
                    <label> هل للصتف وحده تحزئة ابن</label> <br>
                    @if($data['does_has_retailunit'] ==1) نعم @else لا @endif
                </td>
                @if($data['does_has_retailunit'] ==1)
                <td>
                    <label> وحده قياس التجزئة</label> <br>
                    {{ $data['retail_uom_name']}}
                </td>
                <td>
                    <label> عدد وحدات التجزئة بالنسبة الاب</label> <br>
                    {{ $data['retail_uom_quantityToParent']}}
                </td>

                @endif
            </tr>


            <tr>
                <td>
                    <label> سعر القطاعي جملة بوحدة ({{ $data['uom_name']}} )</label> <br>
                    {{ $data['price']}}
                </td>

                <td>
                    <label> سعر النص جملة بوحدة ({{ $data['uom_name']}} )</label> <br>
                    {{ $data['nos_gomla_price']}}
                </td>
                <td>
                    <label> سعر جملة بوحده ({{ $data['uom_name']}} )</label> <br>
                    {{ $data['gomla_price']}}
                </td>



            </tr>
            <tr>
                <td>
                    <label> سعر تكلفة الشراء لوحده({{ $data['uom_name']}} )</label> <br>
                    {{ $data['cost_price']}}
                </td>
                @if($data['does_has_retailunit'] ==1)
                <td>
                    <label> سعر النص جملة بوحده ({{ $data['retail_uom_name']}} )</label> <br>
                    {{ $data['nos_gomla_price_retail']}}
                </td>
                <td>
                    <label> سعر جملة بوحده ({{ $data['retail_uom_name']}} )</label> <br>
                    {{ $data['gomla_price_retail']}}
                </td>
                @endif
            </tr>
            <tr>

            </tr>

            <tr>
                <td colspan="1">
                    <label> سعر تكلفة الشراء بوحده ({{ $data['retail_uom_name']}} )</label> <br>
                    {{ $data['cost_price_retail']}}
                </td>
                <td colspan="2">
                    <label> كمية الصنف الحالية : ({{ $data['all_quantity'] *1}} {{ $data['retail_uom_name']}} )</label> <br>

                </td>
            </tr>

            <tr>
                <td>
                    <label> هل للصنف سعر ثابت</label> <br>
                    @if($data['has_fixced_price']==1) نعم @else لا @endif
                </td>
                <td colspan="2">
                    <label> حاله التفعيل</label> <br>
                    @if($data['active']==1) نعم @else لا @endif
                </td>

            </tr>



            <tr>
                <td> لوجو الصنف </td>

                <td colspan="2">
                    <div class="image">
                        <img class="custom_img" src="{{asset('admin/uploads').'/'.$data['photo']}}" alt="لوجو الصنف ">
                    </div>
                </td>

            </tr>

            <tr>
                <td> تاريخ اخر تحديث</td>
                <td colspan="2">
                    @if($data['update_by']>0 and $data['update_by']!=null )
                    @php
                    $dt=new DateTime($data['updated_at']);
                    $date=$dt->format("Y-m-d");
                    $time=$dt->format("h:i ");
                    $newDateTime=date("A",strtotime($time));
                    $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                    @endphp
                    {{ $date }}
                    {{ $time }}
                    {{ $newDateTimeType }}
                    <span style="margin-right: 50px;">بواسطة</span>
                    {{ $data['update_by'] }}



                    @else
                    لايوجد تحديث
                    @endif

                    <a href="{{ route('inv_itemcard.edit',$data['id']) }}" class="btn btn-sm btn-success" style="margin-right: 50px;"> تعديل</a>
                </td>


            </tr>

        </table>

        <hr style="border: 1px solid #3c8dbc;">

        <div class="row">
            <div class="col-md-12" style="margin-bottom: 20px;">
                <h3 class="customh3">
                    سجل الحركة على الصنف (كارت الصنف )
                </h3>
            </div>
            <div class="col-md-4">
                <div class="form-group ">
                    <label> بحث بالمخازن</label>
                    <select name="stores" id="stores" class="form-control select2 ">
                        <option value="all">بحث بالكل </option>
                        @if (@isset($stores) && !@empty($stores))
                        @foreach ($stores as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بقسم الحركة </label>
                    <select name="inv_itemcard_movements_categories" id="inv_itemcard_movements_categories" class="form-control select2 ">
                        <option value="all">بحث بكل الخزن</option>
                        @if (@isset($inv_itemcard_movements_categories) && !@empty($inv_itemcard_movements_categories))
                        @foreach ($inv_itemcard_movements_categories as $info )
                        <option value="{{ $info->id }}"> {{ $info->name }} </option>
                        @endforeach
                        @endif
                    </select>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بنوع الحركة </label>
                    <select name="inv_itemcard_movements_types" id="inv_itemcard_movements_types" class="form-control select2 ">
                        <option value="all">بحث بكل الخزن</option>
                        @if (@isset($inv_itemcard_movements_types) && !@empty($inv_itemcard_movements_types))
                        @foreach ($inv_itemcard_movements_types as $info )
                        <option value="{{ $info->id }}"> {{ $info->type }} </option>
                        @endforeach
                        @endif
                    </select>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث من تاريخ حركة </label>
                    <input name="from_order_date" id="from_order_date" type="date" class="form-control">
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث الى تاريخ </label>
                    <input name="to_order_date" id="to_order_date" type="date" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label> بحث بنوع الحركة </label>
                    <select name="sort_id" id="sort_id" class="form-control select2 ">
                        <option value="DESC"> بحث ترتيب تنازلي </option>
                        <option value="ASC"> بحث ترتيب تصاعدي </option>
                    </select>
                </div>
            </div>
        </div>

        <div id='ajax_search_show' class="text-center">
            <button class="btn btn-info" id="show">عرض سجل الحركة </button>
        </div>


    </div>
</div>

@endsection

@section('script')
<script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/inv_itemcard.js')}}"></script>

<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>

@endsection