<div id="ajax_responce_serarchDiv">
   @if (@isset($allitemscarddata) && !@empty($allitemscarddata) && count($allitemscarddata) >0 )
   @php
   $i=1;
   @endphp
   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">
         <th style="width: 10%;">كود الي</th>
         <th style="width: 20%;">الاسم </th>
         <th style="width: 70%;">الكميات بالمخازن</th>
      </thead>
      <tbody>
         @foreach ($allitemscarddata as $info )
         <tr>
            <td>{{ $info->item_code }}</td>
            <td>{{ $info->name }}</td>
            <td>
               كل الكميات بنتيجة البحث ( {{ $info->researchQuantity *1 }} {{ $info->uom_name  }} ) <br> <br>

               @if( !@empty($info->allitembatches) and count($info->allitembatches) > 0)
               <h3 style="font-size: 15px; text-align: center; color: brown;">تفاصيل كميات الصنف بالمخازن</h3>
               <table id="example2" class="table table-bordered table-hover">
                  <thead class="bg-info">
                     <td>رقم الباتش </td>
                     <td> المخزن</td>
                     <td> الكمية</td>
                  </thead>
                  <tbody>

                     @foreach ($info->allitembatches as $det )
                     <tr @if( $det->quantity==0) class="bg-warning" @endif>
                        <td>{{$det->auto_serial}}</td>
                        <td>{{$det->store_name}}</td>
                        <td>
                           عدد ({{$det->quantity}}) {{$info->uom_name}} بإجمالي تكلفة ({{$det->toatal_cost_price * 1 }} جنية ) <br>
                           @if($info->item_type == 2)
                           تاريخ الانتاج ( {{$det->production_date}}) <br>
                           تاريخ الانتهاء ( {{$det->expired_date}} )
                           @endif
                           @if($info->does_has_retailunit == 1 )
                           <br>
                           <span style="color: brown;">مايوازي بوجدة التجزئة </span> <br>
                           عدد ({{$det->retail_quantity}}) {{$info->retail_uom_name}} بإجمالي تكلفة ({{$det->toatal_cost_price * 1}}) <br>
                           بسعر ({{$det->toatal_cost_price * 1 }} جنية ) لوحدة {{$info->retail_uom_name}}
                           @endif
                        </td>

                     </tr>
                     @endforeach

                  </tbody>
               </table>

               @else
               <h3 style="font-size: 15px; text-align: center; color: brown;"> لا توجد كميات في المخازن </h3>
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
   {{ $allitemscarddata->links() }}
   @else
   <div class="alert alert-danger">
      عفوا لاتوجد بيانات لعرضها !!
   </div>
   @endif
</div>