@extends('layouts.admin')

@section('title')
 الصلاحيات 
@endsection
@section('contentheader')
المستخدمين 
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.treasures.index') }}"> الصلاحيات </a>
@endsection
@section('contentheaderactive')



@section('contentheaderactive')
عرض صلاحيات الخاصة
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">  تفاصيل المستخدم  </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(@isset($data) && !@empty($data))
                <table id="example2" class="table table-bordered table-hover">

                    <tr>

                        <td class="width30">اسم المستخدم   </td>
                        <td>{{ $data['name']}} </td>
                    </tr>
                   
               
                    <tr>
                        <td class="width30">  هل الرئيسية </td>
                        <td>
                            @if($data['active']==1) نعم @else لا  @endif
                        </td>
                    </tr>

                   

                    <tr>
                        <td class="width30"> تاريخ  الإضافة</td>
                        <td>
                          
                            @php
                            $dt=new DateTime($data['created_at']);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i ");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            <span style="margin-right: 50px;">بواسطة</span>
                            {{ $data['added_by'] }}



                          

                        </td>


                    </tr>
                    <tr>
                        <td class="width30"> تاريخ اخر تحديث</td>
                        <td>
                            @if($data['updated_by']>0 and $data['updated_by']!=null )
                            @php
                            $dt=new DateTime($data['updated_at']);
                            $date=$dt->format("Y-m-d");
                            $time=$dt->format("h:i ");
                            $newDateTime=date("A",strtotime($time));
                            $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                            @endphp
                            {{ $date }}
                            {{ $time }}
                            {{ $newDateTimeType }}
                            <span style="margin-right: 50px;">بواسطة</span>
                            {{ $data['updated_by'] }}



                            @else
                            لايوجد تحديث
                            @endif

                            <a href="{{route('admin.treasures.edit',$data['id'])}}" class="btn btn-sm btn-success" style="margin-right: 50px;"> تعديل</a>
                        </td>


                    </tr>

                </table>


                <!-- treasuries delivery -->
                <div class="card-header row">
                    <div class="col-md-6">
                        <h5> الخزن المضافه لصلاحيات المستخدم   </h5>
                    </div>   
                    <div class="col-md-6 text-right"> 
                        <button  data-toggle ='modal' data-target = '#Add_treasures_model' class="btn btn-sm btn-primary" > اضافة جديد</button>
                    </div>
                </div>    
                <div id="ajax_responce_serarchDiv">
                @if (@isset($treasuries_delivery) && !@empty($treasuries_delivery) && count($treasuries_delivery) >0 )
                    @php
                    $i=1;   
                    @endphp
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>مسلسل</th>
                            <th>اسم الخزنة</th>
                            <th>تاريخ الايضافة</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @foreach ($treasuries_delivery as $info )
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $info->name }}</td>
                                <td>
                                
                                @php
                                $dt=new DateTime($info->created_at);
                                $date=$dt->format("Y-m-d");
                                $time=$dt->format("h:i ");
                                $newDateTime=date("A",strtotime($time));
                                $newDateTimeType= (($newDateTime=='AM')?'مساء ':'صباحا');
                                @endphp
                                {{ $date }}
                                {{ $time }}
                                {{ $newDateTimeType }}
                                <span style="margin-right: 50px;">بواسطة</span>
                                {{ $info->added_by }}



                                

                            </td>

                            <td>
                                <a class="btn btn-sm btn-danger are_you_sure" href="{{route('admin.delete_treasures_delivery',$info->id)}}">حذف</a>
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
            </div>

             <!-- end treasuries delivery -->
                @else
                    <div class="alert alert-danger">
                        عفوا لا توجد بيانات لعرضها
                    </div>
                @endif

               

            </div>
        </div>
    </div>



    <div class="modal fade " id="Add_treasures_model">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 class="modal-title">إضافة خزن للمستخدم  </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               
                <div class="modal-body" id="Add_treasures_body" style="background-color: white !important; color:black">
        
                <form action="{{ route('admin.admins_accounts.store_treasures_to_admin',$data['id']) }}" method="post">
                    @csrf
                    
                    <div class="form-group">
                        <label > بيانات الخزن </label>
                        <select name="treasures_id" id="treasures_id" class="form-control ">
                            <option value="">اختر الخزنة</option>
                            @if (@isset($treasures) && !@empty($treasures))
                                @foreach ($treasures as $info )
                                    <option value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                       
                    </div>

                    <div class="form-group text-center" style="margin-bottom: 50px;">
                        <button type="submit" class="btn btn-success btn-sm"> إضافة خزنة للمسته </button>
                    </div>

                </form>
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-info">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>

            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    @endsection