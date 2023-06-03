@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;   
@endphp
<table id="example2" class="table table-bordered table-hover">
                  <thead class="custom_thead">
                     <th>مسلسل</th>
                     <th>اسم المخزن</th>
                     <th> هل الرئيسية</th>
                     <th>نوع الوحدات </th>
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
                        <td>@if($info->active==1) وحدات أب @else وحدة تجزئة @endif</td>
                        <td>{{ $info->address }}</td>
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

                        <td>
                           <a href="{{ route('admin.uoms.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                           <a href="{{ route('admin.uoms.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
                        </td>
                     </tr>
                     @php
                     $i++;
                     @endphp
                     @endforeach
                  </tbody>
               </table>
<br>
<div class="col-md-12" id="ajax_pagination_in_search">
   {{ $data->links() }}
</div>
@else
<div class="alert alert-danger">
   عفوا لاتوجد بيانات لعرضها !!
</div>
@endif