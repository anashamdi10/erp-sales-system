@extends('layouts.admin')
@section('title')
عرض صنف
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">بيانات الضبط العام </h3>
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
                        <td colspan="3">
                            <label> سعر تكلفة الشراء بوحده ({{ $data['retail_uom_name']}} )</label> <br>
                            {{ $data['cost_price_retail']}}
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
                        <td> لوجو للشركة </td>

                        <td colspan="2">
                            <div class="image">
                                <img class="custom_img" src="{{asset('admin/uploads').'/'.$data['photo']}}" alt="لوجو الشركة ">
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




            </div>
        </div>
    </div>
    @endsection