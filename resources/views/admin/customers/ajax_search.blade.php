<div id="ajax_responce_serarchDiv">
   @if (@isset($data) && !@empty($data) && count($data) >0 )
   @php
   $i=1;
   @endphp
   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">
         <th>الاسم </th>
         <th>الكود </th>
         <th> رقم الحساب </th>
         <th> الرصيد </th>
         <th> العنوان </th>
         <th> ملاحظات </th>
         <th>حالة التفعيل</th>
         <th></th>
      </thead>
      <tbody>
         @foreach ($data as $info )
         <tr>
            <td>{{ $info->name }}</td>
            <td>{{ $info->customer_code }}</td>
            <td>{{ $info->	account_number }}</td>
            <td>
               @if($info->is_parent==0)
               @if($info->current_blance > 0 )
               مدين ب ({{ $info->current_blance *1 }}) جنيه
               @elseif($info->current_blance < 0) دائن ب ({{ $info->current_blance *(-1) }}) جنيه @else متزن @endif @else من ميزان المراجعه @endif </td>
            <td>{{ $info->	address }}</td>
            <td>{{ $info->	notes }}</td>


            <td>@if($info->active==1) مفعل @else معطل @endif</td>

            <td class="text-center">
               <a href="{{ route('admin.customer.edit',$info->id) }}" class="btn btn-sm   btn-primary">تعديل</a>
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