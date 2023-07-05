 <h3 class="card-title card_title_center"> الأصناف المضافة على الفاتورة</h3>
 <table id="example2" class="table table-bordered table-hover">
     <thead class="custom_thead">
         <th>المخزن </th>
         <th> نوع البيع </th>
         <th> الصنف</th>
         <th> وحدة البيع </th>
         <th> سعر الوحدة </th>
         <th>الكمية </th>

         <th>الإجمالي </th>
         <th></th>

     </thead>

     <tbody id='itemsrowtableContainterBody'>
         @foreach ($sales_items_sales as $info )
         <tr>
             <td>{{ $info->store_name }}</td>
             <td>
                 @if ($info->sales_item_type == 1)
                 قطاعي
                 @elseif($info->sales_item_type == 2)
                 نص جملة
                 @else
                 جملة
                 @endif
             </td>
             <td>{{ $info->item_name }}</td>
             <td>{{ $info->uom_name }}</td>
             <td>{{ $info->unit_price*1 }}</td>
             <td>{{ $info->quantity }}</td>
             <td>{{ $info->total_price*1 }}</td>
             <td>
                 <button class="btn remove_current_row btn-sm btn-danger">حذف</button>
             </td>

         </tr>
         @endforeach
     </tbody>

 </table>