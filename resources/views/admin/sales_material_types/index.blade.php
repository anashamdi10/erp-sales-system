@extends('layouts.admin')
@section('title')
الضبط العام
@endsection
@section('contentheader')
فئات الفواتير
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.sales_material_types.index') }}"> فئات الفواتير </a>
@endsection
@section('contentheaderactive')
عرض
@endsection
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h3 class="card-title card_title_center">بيانات فئات الفواتير</h3>
            <input type="hidden" id="token_search" value="{{csrf_token() }}">
            <input type="hidden" id="ajax_search_url" value="{{ route('admin.treasures.ajax_search') }}">
            <a href="{{ route('admin.sales_material_types.create') }}" class="btn btn-sm btn-success">اضافة جديد</a>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <div id="ajax_responce_serarchDiv">
               @if (@isset($data) && !@empty($data) && count($data) >0 )
               @php
               $i=1;
               @endphp
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">
                     <th>مسلسل</th>
                     <th>اسم الفئة</th>
                     <th>حالة التفعيل</th>
                     <th> تاريخ الاضافة</th>
                     <th> تاريخ تحديث</th>
                     <th></th>

                  </thead>
                  <tbody>
                     @foreach ($data as $info )
                     <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $info->name }}</td>


                        <td>@if($info->active==1) مفعل @else معطل @endif</td>


                        <td>

                           @php
                           $dt=new DateTime($info->created_at);
                           $date=$dt->format("Y-m-d");
                           $time=$dt->format("h:i ");
                           $newDateTime=date("A",strtotime($time));
                           $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                           @endphp
                           {{ $date }} <br>
                           {{ $time }}
                           {{ $newDateTimeType }} <br>
                           <span>بواسطة</span>
                           {{ $info->added_by }}





                        </td>

                        <td>
                           @if( $info->updated_at!=null )
                           @php
                           $dt=new DateTime($info->updated_at);
                           $date=$dt->format("Y-m-d");
                           $time=$dt->format("h:i ");
                           $newDateTime=date("A",strtotime($time));
                           $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                           @endphp
                           {{ $date }}<br>
                           {{ $time }}
                           {{ $newDateTimeType }}<br>
                           <span>بواسطة</span>
                           {{ $info->updated_by }}



                           @else
                           لايوجد تحديث
                           @endif

                        </td>

                        <td class="text-center">
                           <a href="{{ route('admin.sales_material_types.edit',$info->id) }}" class="btn btn-sm  btn-primary ">تعديل</a>
                           <!-- <a href="{{ route('admin.delete_sales_material_types',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a> -->
                        </td>
                     </tr>
                     @php
                     $i++;
                     @endphp
                     @endforeach
                  </tbody>
               </table>
               <br>
               {{ $data->links() }}
               @else
               <div class="alert alert-danger">
                  عفوا لاتوجد بيانات لعرضها !!
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection