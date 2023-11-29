@if (@isset($data) && !@empty($data) && count($data) >0 )
@php
$i=1;
@endphp
<table id="example2" class="table table-bordered table-hover">
   <thead class="custom_thead">
      <th>كود</th>
      <th> تاريخ الفاتورة</th>
      <th> العميل</th>
      <th> فئة الفاتورة</th>
      <th> نوع الفاتورة</th>

      <th> اجمالي الفاتورة</th>
      <th>حالة الفاتورة</th>

      <th></th>

   </thead>
   <tbody>
      @foreach ($data as $info )
      <tr>
         <td>{{ $info->auto_serial }}</td>
         <td>{{ $info->invoice_date }}</td>
         <td>{{ $info->customer_name ? $info->customer_name : "بدن عميل" }}</td>
         <td>{{ $info->material_types_name }}</td>
         <td>@if($info->pill_type==1) كاش @elseif($info->pill_type==2) اجل @else غير محدد @endif</td>
         <td>{{ $info->total_cost*(1) }}</td>

         <td>@if($info->is_approved==1) معتمدة @else مفتوحة @endif</td>

         <td style="text-align: center;">

            @if($info->is_approved==0)
            <button data-auto_serial="{{$info->auto_serial}}" class="btn btn-sm load_update_sales_invoice  btn-primary">تعديل</button>
            <a href="{{ route('admin.SalesInvoices.delete_invoice',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>

            @endif

            <button data-autoserial="{{ $info->auto_serial }}" id='load_invoice_details_modal' class="btn btn-sm load_invoice_details_modal btn-info">عرض</button>

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