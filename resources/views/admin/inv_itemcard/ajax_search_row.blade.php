<div id="ajax_search_show">
   @if (@isset($data) && !@empty($data) && count($data) >0 )
   @php
   $i=1;
   @endphp
   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead text-center">
         <th style="width: 10%;">المخزن</th>
         <th style="width: 10%;">الفسم </th>
         <th style="width: 20%;"> الحركة </th>
         <th style="width: 20%;">الكمية قبل الحركة </th>
         <th style="width: 20%;"> الكمية قبل الحركة </th>
         <th style="width: 20%;"> </th>

      </thead>
      <tbody class="text-center">
         @foreach ($data as $info )
         <tr>
            <td>{{$info->store_name}}</td>
            <td>{{$info->type_name}}</td>
            <td>{{$info->categories_name}}</td>
            <td>
               <span style="color: brown;"> الكمية بالمخزن الحالي  {{$info->quantity_befor_move_store }}  </span> <br>
               <span style="color: blue;"> الكمية بكل المخازن  {{$info-> quantity_befor_movement}}  </span>
            </td>
            <td>
               <span style="color: brown;"> الكمية بالمخزن الحالي{{$info->quantity_after_move_store }} </span> <br>
               <span style="color: blue;"> الكمية بكل المخازن {{$info-> quantity_after_move}} </span>
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
               {{ $info->added_by     }}



               @else
               لايوجد تحديث
               @endif


            </td>
         </tr>
         @php
         $i++;
         @endphp
         @endforeach
      </tbody>
   </table>
   <br>
   <div class="col-md-12" id="ajax_pagination_in_search_show">
      {{ $data->links() }}
   </div>
   @else
   <div class="alert alert-danger">
      عفوا لاتوجد بيانات لعرضها !!
   </div>
   @endif
</div>
