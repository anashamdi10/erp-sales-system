<div id="ajax_responce_serarchDiv">
   @if (@isset($data) && !@empty($data) && count($data) >0 )
   @php
   $i=1;
   @endphp
   <table id="example2" class="table table-bordered table-hover">
      <thead class="custom_thead">

         <th>الاسم </th>
         <th>الكود </th>
         <th>اسم الفئة </th>
         <th> رقم الحساب </th>
         <th> الرصيد </th>
         <th>حالة التفعيل</th>

         <th></th>

      </thead>
      <tbody>
         @foreach ($data as $info )

         <tr>

            <td>{{ $info->name }}</td>
            <td>{{ $info->supplier_code }}</td>
            <td>{{ $info->categories_name }}</td>
            <td>{{ $info->	account_number }}</td>

            <td></td>



            <td>@if($info->active==1) مفعل @else معطل @endif</td>

            <td>
               <a href="{{ route('admin.suppliers.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
               <a href="{{ route ('admin.suppliers.delete',$info->id)}}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
               <a href="{{ route('inv_itemcard.show',$info->id) }}" class="btn btn-sm   btn-info">عرض</a>
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