<div id="ajax_responce_serarchDiv">
   
   
   @if (@isset($data) && !@empty($data) && count($data) >0 )
      @php
         $i=1;
      @endphp
      <table id="example2" class="table table-bordered table-hover">
         <thead class="custom_thead">

            <th>الاسم </th>
            <th> رقم الحساب </th>
            <th>النوع </th>
            <th>هل الاب </th>
            <th>الحساب الاب </th>
            <th> الرصيد </th>
            <th>حالة التفعيل</th>

            <th></th>

         </thead>
         <tbody>
            @foreach ($data as $info )

            <tr>

               <td>{{ $info->name }}</td>
               <td>{{ $info->	account_number }}</td>

               <td>{{ $info->account_type_name }}</td>
               <td>@if($info->is_parent==1) نعم @else لا @endif</td>
               <td>{{$info->parent_account_name}}</td>
               <td>{{ $info->current_blance }}</td>



               <td>@if($info->is_archived==0) مفعل @else معطل @endif</td>

               <td>
                  <a href="{{ route('admin.accounts.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
                  <a href="{{ route('inv_itemcard.delete',$info->id) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>
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
   <div class="col-md-12" id="ajax_pagination_in_search">
      {{ $data->links() }}
   
   </div>

   @else
      <div class="alert alert-danger">
         عفوا لاتوجد بيانات لعرضها !!
      </div>
   @endif
</div>