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
إضافة
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> إضافة خزنة للمستلم   ({{$data['name']}})</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{route('admin.treasures.store_treasures_delivery',$data['id'])}}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <select name="treasures_can_delivery_id" id="treasures_can_delivery_id" class="form-control ">
                            <option value="">اختر الخزنة</option>
                            @if (@isset($treasures) && !@empty($treasures))
                                @foreach ($treasures as $info )
                                    <option @if(old('treasures_can_delivery_id')==$info->id) selected="selected" @endif value="{{ $info->id }}"> {{ $info->name }} </option>
                                @endforeach
                            @endif
                        </select>
                        @error('treasures_can_delivery_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="form-group text-center" style="margin-bottom: 50px;">
                        <button type="submit" class="btn btn-primary btn-sm"> حفظ </button>
                        <a href="{{route('admin.treasures.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>

                </form>




            </div>
        </div>
    </div>
    @endsection