@extends('layouts.admin')
@section('title')
شفتات الخزن 
@endsection
@section('contentheader')
حركة الخزينة 
@endsection
@section('contentheaderlink')
<a href="{{ route('admin.admin_shift.index') }}"> شفتات الخزن  </a>
@endsection
@section('contentheaderactive')
إضافة
@endsection


@section('contentheaderactive')
إضافة 
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center">    استلام خزنه لسيفت جديد     </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                
                    <form action="{{route('admin.admin_shift.store')}}" method="post" >
                        @csrf

                        <div class="form-group">
                        <label>  بيانات الخزن المضافة  لصلاخيات  </label>
                        <select name="treasures_id" id="treasures_id" class="form-control ">
                            <option value="0" selected> هو اب</option>
                            @if (@isset($admin_treasures) && !@empty($admin_treasures))
                                @foreach ($admin_treasures as $info )
                                    <option  value="{{ $info->treasures_id }}" @if($info->avalible == false) disabled  @endif >
                                         {{ $info->name }}  @if($info->avalible == false) (غير متاحه لإستلامها حاليا مع مستخدم اخر ) @endif  
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('treasures_id')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                       
                       

                        <div class="form-group text-center" style="margin-bottom: 50px;">
                            <button type="submit" class="btn btn-primary btn-sm"> إضافة </button>
                            <a href="{{route('admin.stores.index')}}" class="btn btn-sm btn-danger">الغاء</a>
                        </div>

                    </form>
               



            </div>
        </div>
    </div>
    @endsection