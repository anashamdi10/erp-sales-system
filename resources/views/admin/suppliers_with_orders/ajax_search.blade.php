<div id="ajax_responce_serarchDiv">
   @if (@isset($data) && !@empty($data) && count($data) >0 )
      @php
         $i=1;
      @endphp
      <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">
            <th>كود</th>
            <th> المورد</th>
            <th> نوع  الفاتورة</th>
            <th> تاريخ الفاتورة</th>
                     
            <th>حالة الفاتورة</th>
                     
            <th></th>

         </thead>
         <tbody>
            @foreach ($data as $info )
               <tr>
                  <td>{{ $info->auto_serial }}</td>
                  <td>{{ $info->supplier_name }}</td>
                  <td>{{ $info->order_date }}</td>
                  <td>@if($info->pill_type==1)  كاش  @elseif($info->pill_type==2) أجل@else غير محدد  @endif</td>
                  <td>@if($info->is_approved==0) مفتوحة @else معتمدة @endif</td>
                  <td style="text-align: center;">
                     <a href="{{ route('admin.uoms.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                     <a href="{{ route('admin.uoms.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
                     <a href="{{ route('admin.uoms.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-success">اعتماد</a>
                     <a href="{{ route('admin.suppliers_orders.show',$info->id) }}" class="btn btn-sm   btn-info">الاصناف </a>
                  </td>
               </tr>
               @php
                  $i++;
               @endphp
            @endforeach
         </tbody>
      </table>
      <br>
      <div>
         {{ $data->links() }}
      </div>
      @else
         <div class="alert alert-danger">
            عفوا لاتوجد بيانات لعرضها !!
         </div>
      @endif
</div>