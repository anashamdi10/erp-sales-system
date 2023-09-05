<div id="ajax_responce_serarchDiv">
   @if (@isset($data) && !@empty($data) && count($data) >0 )
   @php
   $i=1;
   @endphp
   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">
         <th> المخزن</th>
         <th>القسم </th>
         <th> الحركة </th>
         <th>الكمية قبل الحركة </th>
         <th>الكمية بعد الحركة </th>



         <th></th>
      </thead>
      <tbody>
         @foreach ($data as $info )
         <tr>
            <td>{{ $info->store_name }}</td>
            <td>{{$info->categories_name}}</td>
            <td>{{ $info->type_name }}</td>


            <td>
               <span style="color: brown;"> الكمية بالمخزن الحالي <br>{{ $info->quantity_befor_move_store }} </span>

               <br>
               <span style="color:blue;"> الكمية بكل المخازن <br>{{ $info->quantity_befor_movement }} </span>

            </td>

            <td>
               <span style="color: brown;"> الكمية بالمخزن الحالي <br>{{ $info->quantity_after_move_store }} </span>

               <br>
               <span style="color:blue;"> الكمية بكل المخازن <br>{{ $info->quantity_after_move }} </span>

            </td>



            <td>

               @php
               $dt=new DateTime($info->created_at);
               $date=$dt->format("Y-m-d");
               $time=$dt->format("h:i");
               $newDateTime=date("A",strtotime($time));
               $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء');
               @endphp
               {{ $date }} <br>
               {{ $time }}
               {{ $newDateTimeType }} <br>
               بواسطة
               {{ $info->added_by}}


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