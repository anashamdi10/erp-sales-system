<div id="ajax_responce_serarchDiv">

   <p>اجمالي التحصيل نتيجة البحث ({{$total_collect_search}})</p>
   @if (@isset($data) && !@empty($data) && count($data) >0 )

   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">
         <th>كود الي</th>
         <th>رقم الايصال</th>
         <th> الخزنة</th>
         <th> المبلغ </th>
         <th> الحركة </th>
         <th> الحساب المالي </th>
         <th>البيان</th>
         <th> المستخدم</th>
         <th></th>
      </thead>
      <tbody>
         @foreach ($data as $info )
         <tr>
            <td>{{ $info->auto_serial }}</td>
            <td>{{ $info->isal_number }}</td>
            <td>{{ $info->treasures_name}} <br>({{ $info->shift_code}}) </td>
            <td>{{ $info->money * 1}}</td>
            <td>{{ $info->mov_type_name }}</td>
            <td>
               @if($info->account_name != null)
               {{ $info->account_name }}({{ $info->account_type_name }})
               @else
               بدون حساب مالي
               @endif
            </td>
            <td>{{ $info->bayan}}</td>
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
               <a href="{{ route('admin.treasures.edit',$info->id) }}" class="btn btn-sm  btn-primary">طباعه</a>
               <a href="{{ route('admin.treasures.details',$info->id) }}" class="btn btn-sm  btn-info">المزيد</a>
            </td>
         </tr>

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