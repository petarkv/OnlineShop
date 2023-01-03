@extends('layouts.admin_layout.admin_design')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">        
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger" style="margin-top: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
            @endif

            @if(Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="margin-top: 10px;">
                    <strong>{{ Session::get('success_message') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Session::has('error_message'))
                <div class="alert alert-danger" role="alert" style="margin-top: 10px;">
                    <strong>{{ Session::get('error_message') }}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form name="couponForm" id="couponForm" 
            @if (empty($coupon['id']))
                action="{{ url('admin/add-edit-coupon') }}"
            @else
                action="{{ url('admin/add-edit-coupon/'.$coupon['id']) }}"
            @endif 
            method="POST" enctype="multipart/form-data">@csrf
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }} Form</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">                    
                        <div class="col-md-6">
                            @if(empty($coupon['coupon_code']))
                            <div class="form-group">
                                <label for="coupon_option">Coupon Option</label><br>
                                <span><input id="automaticCoupon" type="radio" name="coupon_option" value="Automatic" checked=""> Automatic</span>&nbsp;&nbsp;&nbsp;
                                <span><input id="manualCoupon" type="radio" name="coupon_option" value="Manual"> Manual</span>
                            </div>
                            <div class="form-group" style="display: none;" id="couponCodeField">
                                <label for="coupon_code">Coupon Code</label>
                                <input type="text" class="form-control" name="coupon_code" id="coupon_code" 
                                placeholder="Enter Coupon Code">
                            </div>
                            @else
                            <input type="hidden" name="coupon_option" value="{{ $coupon['coupon_option'] }}">
                            <input type="hidden" name="coupon_code" value="{{ $coupon['coupon_code'] }}">
                            <div class="form-group" id="couponCodeField">
                                <label for="coupon_code">Coupon Code</label><br>
                                <span>{{ $coupon['coupon_code'] }}</span>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="coupon_type">Coupon Type</label><br>
                                <span><input type="radio" name="coupon_type" value="Multiple Times"
                                    @if(isset($coupon['coupon_type'])&&$coupon['coupon_type']=="Multiple Times") checked="" 
                                    @elseif(!isset($coupon['coupon_type'])) checked="" @endif>
                                     Multiple Times</span>&nbsp;&nbsp;&nbsp;
                                <span><input type="radio" name="coupon_type" value="Single Times"
                                    @if(isset($coupon['coupon_type'])&&$coupon['coupon_type']=="Single Times") checked="" @endif>
                                     Single Times</span>
                            </div>
                            <div class="form-group">
                                <label for="amount_type">Amount Type</label><br>
                                <span><input type="radio" name="amount_type" value="Percentage" 
                                    @if(isset($coupon['amount_type'])&&$coupon['amount_type']=="Percentage") checked="" 
                                    @elseif(!isset($coupon['amount_type'])) checked="" @endif>
                                     Percentage</span>&nbsp;(in %)&nbsp;&nbsp;
                                <span><input type="radio" name="amount_type" value="Fixed"
                                    @if(isset($coupon['amount_type'])&&$coupon['amount_type']=="Fixed") checked="" @endif>
                                     Fixed</span>&nbsp;(in EUR)
                            </div> 
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" name="amount" id="amount" 
                                placeholder="Enter Amount" required="" @if(isset($coupon['amount'])) value="{{ $coupon['amount'] }}" @endif>
                            </div>                                                  
                        </div>
                    </div>
                    <!-- /.row -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categories">Select Categories</label>
                                <select name="categories[]" class="form-control select2" multiple="" 
                                        style="width: 100%;" required="">
                                    <option value="">--- Select ---</option>
                                        @foreach ($categories as $section)
                                            <optgroup label="{{ $section['name'] }}"></optgroup>
                                            @foreach ($section['categories'] as $category)
                                                <option value="{{ $category['id'] }}" {{-- @if (!empty(@old('category_id')) && 
                                                    $category['id']==@old('category_id')) selected=""
                                                    @elseif(!empty($productdata['category_id']) && $productdata['category_id']==$category['id'])
                                                    selected="" @endif --}}
                                                    @if (in_array($category['id'],$selCats)) selected="" @endif>&nbsp;&nbsp;&nbsp;--&nbsp;&nbsp;
                                                    {{ $category['category_name'] }}</option>
                                                @foreach ($category['subcategories'] as $subcategory)
                                                    <option value="{{ $subcategory['id'] }}" {{-- @if (!empty(@old('category_id')) && 
                                                    $subcategory['id']==@old('category_id')) selected="" 
                                                    @elseif(!empty($productdata['category_id']) && $productdata['category_id']==$category['id'])
                                                    selected="" @endif --}}
                                                    @if (in_array($subcategory['id'],$selCats)) selected="" @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**&nbsp;&nbsp;
                                                    {{ $subcategory['category_name'] }}</option>
                                                @endforeach
                                            @endforeach
                                        @endforeach                                           
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="users">Select Users</label>
                                <select name="users[]" class="form-control select2" multiple="" 
                                       data-live-search="true" style="width: 100%;">
                                    <option value="">--- Select ---</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user['email'] }}" @if (in_array($user['email'],$selUsers)) selected="" @endif>{{ $user['email'] }}</option>
                                        @endforeach                                           
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Expiry Date</label>
                                <div class="controls">
                                  <input type="text"  name="expiry_date" id="expiry_date" autocomplete="off" required=""
                                  @if(isset($coupon['expiry_date'])) value="{{ $coupon['expiry_date'] }}" @endif>
                                </div>
                            </div>                          
                        </div>
                        <!-- /.col -->
                </div>
                <!-- /.card-body -->  
            </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>            
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection