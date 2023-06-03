@extends('layouts.admin')

@section('title')
الضبط العام
@endsection

@section('contentheader')
الضبط
@endsection
@section('contentheaderlink')
<a href="{{route('admin.adminPanelSettings.index')}}">الضبط</a>
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
                @if(@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>

                        <td class="width30">اسم الشركة </td>
                        <td>{{ $data['system_name']}} </td>
                    </tr>
                    <tr>
                        <td class="width30">كود الشركة </td>
                        <td>{{ $data['com_code']}} </td>
                    </tr>
                    <tr>
                        <td class="width30">حالة الشركة </td>
                        <td>
                            @if($data['active']==1) مفعل @else غير مفعل @endif
                        </td>
                    </tr>

                    <tr>
                        <td class="width30">عنوان الشركة </td>
                        <td>{{ $data['address']}} </td>
                    </tr>
                    <tr>
                        <td class="width30">هاتف الشركة </td>
                        <td>{{ $data['phone']}} </td>
                    </tr>
                    <tr>
                        <td class="width30"> اسم الحساب المالي للعملاء الاب </td>
                        <td>{{ $data['customer_parent_account_name']}} رقم الحساب مالي ({{$data['customer_parent_account_number']}})</td>
                    </tr>
                    <tr>
                        <td class="width30"> اسم الحساب المالي للموردين الاب </td>
                        <td>{{$data['suppliers_parent_account_name']}} رقم الحساب مالي ({{$data['suppliers_parent_account_number']}})</td>
                    </tr>




                    
                    <tr>
                        <td class="width30">رسالة التنبيه اعلى الشاشة للشركة </td>
                        <td>{{ $data['general_alert']}} </td>
                    </tr>
                    <tr>
                        <td class="width30"> لوجو للشركة </td>
                        <td>
                            <div class="image">
                                <img class="custom_img" src="{{asset('admin/uploads').'/'.$data['photo']}}" alt="لوجو الشركة ">
                            </div>
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

                            <a href="{{route('admin.adminPanelSettings.edit')}}" class="btn btn-sm btn-success" style="margin-right: 50px;"> تعديل</a>
                        </td>


                    </tr>

                </table>
                @else
                <div class="alert alert-danger">
                    عفوا لا توجد بيانات لعرضها
                </div>
                @endif



            </div>
        </div>
    </div>
    @endsection