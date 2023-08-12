@extends('layouts.admin')

@section('title')
العام
@endsection

@section('contentheader')
الخزن
@endsection
@section('contentheaderlink')
<a href="{{route('admin.treasures.index')}}">الخزن</a>
@endsection

@section('contentheaderactive')
عرض التفاصيل
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> تفاصيل الخزنة </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>

                        <td class="width30">اسم الخزنة </td>
                        <td>{{ $data['name']}} </td>
                    </tr>

                    <tr>

                        <td class="width30"> اخر ايصال صرف </td>
                        <td>{{ $data['last_isal_exchange']}} </td>
                    </tr>

                    <tr>

                        <td class="width30">اخر ايصال تحصيل</td>
                        <td>{{ $data['last_isal_collect']}} </td>
                    </tr>

                    <tr>
                        <td class="width30">حالة تفعيل الخزنة </td>
                        <td>
                            @if($data['active']==1) مفعل @else غير مفعل @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="width30"> هل الرئيسية </td>
                        <td>
                            @if($data['is_master']==1) نعم @else لا @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="width30"> تاريخ الإضافة</td>
                        <td>

                            @php
                            $dt=new DateTime($data['created_at']);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i ");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            <span style="margin-right: 50px;">بواسطة</span>
                            {{ $data['added_by'] }}

                        </td>


                    </tr>
                    <tr>
                        <td class="width30"> تاريخ اخر تحديث</td>
                        <td>
                            @if($data['updated_by']>0 and $data['updated_by']!=null )
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
                            {{ $data['updated_by'] }}

                            @else
                            لايوجد تحديث
                            @endif

                            <a href="{{route('admin.treasures.edit',$data['id'])}}" class="btn btn-sm btn-success" style="margin-right: 50px;"> تعديل</a>
                            <a href="{{route('admin.treasures.index')}}" class="btn btn-sm btn-info" style="margin-right: 20px;"> عودة </a>
                        </td>


                    </tr>

                </table>


                <!-- treasuries delivery -->
                <div class="card-header">
                    <h5> الخزن الفرعية التي سوف تسلم عهدتها الى الخزنة ( {{ $data['name'] }})
                        <a href="{{route('admin.treasures.add_treasures_delivery_detials',$data['id'])}}" class="btn btn-sm btn-primary">اضافة</a>
                    </h5>

                </div>
                <div id="ajax_responce_serarchDiv">
                    @if (@isset($treasuries_delivery) && !@empty($treasuries_delivery) && count($treasuries_delivery) >0 )
                    @php
                    $i=1;
                    @endphp
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>مسلسل</th>
                            <th>اسم الخزنة</th>
                            <th>تاريخ الايضافة</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($treasuries_delivery as $info )
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $info->name }}</td>
                                <td>

                                    @php
                                    $dt=new DateTime($info->created_at);
                                    $date=$dt->format("Y-m-d");
                                    $time=$dt->format("h:i ");
                                    $newDateTime=date("A",strtotime($time));
                                    $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                                    @endphp
                                    {{ $date }}
                                    {{ $time }}
                                    {{ $newDateTimeType }}
                                    <span style="margin-right: 50px;">بواسطة</span>
                                    {{ $info->added_by }}
                                </td>

                                <td>
                                    <a class="btn btn-sm btn-danger are_you_sure" href="{{route('admin.delete_treasures_delivery',$info->id)}}">حذف</a>
                                </td>

                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    @else
                    <div class="alert alert-danger">
                        عفوا لاتوجد بيانات لعرضها !!
                    </div>
                    @endif
                </div>

                <!-- end treasuries delivery -->
                @else
                <div class="alert alert-danger">
                    عفوا لا توجد بيانات لعرضها
                </div>
                @endif
            </div>
        </div>
    </div>
    @endsection