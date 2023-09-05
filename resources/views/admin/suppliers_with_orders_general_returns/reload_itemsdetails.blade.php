@if (@isset($details) && !@empty($details) && count($details) >0 )
@php
$i=1;
@endphp

<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">
        <th>مسلسل</th>
        <th> الصنف</th>
        <th>الوحدة </th>
        <th>الكمية</th>
        <th>سعر الوحدة</th>
        <th>الاجمالي</th>
        <th></th>
    </thead>
    <tbody>
        @foreach ($details as $info )
        <tr>
            <td>{{ $i }}</td>
            <td>
                {{ $info->item_card_name }}
                @if($info->item_card_type==2)
                <br> تاريخ الانتاج {{$info-> production_date}}
                <br> تاريخ الانتهاء {{$info-> expire_date}}
                <br> باتش رقم {{$info-> batch_auto_serial}}

                @endif
            </td>
            <td>{{ $info->uom_name }}</td>
            <td>{{ $info->dliverd_quantity }}</td>
            <td>{{ $info->unit_price }}</td>
            <td>{{ $info->total_price }}</td>
            <td>
                @if($data['is_approved']==0)
                <button data-id="{{$info->id}}" class="btn btn-sm load_edit_item_details  btn-primary">تعديل</button>
                <a href="{{ route('admin.suppliers_orders_general_return.delete_details',['id'=>$info->id,'id_parent'=>$data['id'] ]) }}" class="btn btn-sm  are_you_sure btn-danger">حذف</a>

                @endif
            </td>


        </tr>
        @php
        $i++;
        @endphp
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-danger">
    عفوا لاتوجد بيانات لعرضها !!
</div>
@endif