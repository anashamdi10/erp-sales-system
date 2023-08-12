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
         <th> التفعيل</th>

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
            <td>
               @if($info->is_parent==0)
               @if($info->current_blance > 0 )
               مدين ب ({{ $info->current_blance *1 }}) جنيه
               @elseif($info->current_blance < 0) دائن ب ({{ $info->current_blance *(-1) }}) جنيه @else متزن @endif @else من ميزان المراجعه @endif </td>


            <td @if($info->active==1) class = "bg-success text-center" @else class = "bg-danger text-center" @endif >@if($info->active==1) مفعل @else معطل @endif</td>

            <td>
               @if($info->relatediternalaccounts == 0)
               <a href="{{ route('admin.accounts.edit',$info->id) }}" class="btn btn-sm  btn-primary">تعديل</a>
               @else
               <span>يعدل من شاشته </span>
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
   {{ $data->links() }}

   @else
   <div class="alert alert-danger">
      عفوا لاتوجد بيانات لعرضها !!
   </div>
   @endif
</div>