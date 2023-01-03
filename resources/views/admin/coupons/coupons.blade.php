@extends('layouts.admin_layout.admin_design')
@section('content')
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Coupons</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Coupons</li>
            </ol>
          </div>
        </div>

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

      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
            <!-- /.card -->

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">DataTable with Coupons</h3>
                <a href="{{ url('admin/add-edit-coupon') }}" class="btn btn-block btn-success" style="max-width: 150px; float: right;
                 display: inline-block">Add Coupon</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="coupons" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      <th>ID</th>
                      <th>Code</th>                   
                      <th>Coupon Type</th>                    
                      <th>Amount Type</th>                    
                      <th>Amount</th>                    
                      <th>Expiry Date</th>                   
                      <th>Actions</th>                    
                      </tr>
                    </thead>
                    <tbody>

                    @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon['id'] }}</td>                      
                        <td>{{ $coupon['coupon_code'] }}</td>                      
                        <td>{{ $coupon['coupon_type'] }}</td>                      
                        <td>{{ $coupon['amount_type'] }}</td>                      
                        <td>
                            {{ $coupon['amount'] }}
                            @if ($coupon['amount_type']=="Percentage")
                                %
                            @else
                                EUR
                            @endif
                        </td>                        
                        <td>{{ $coupon['expiry_date'] }}</td>                      
                        <td>
                          <a href="{{ url('admin/add-edit-coupon/'.$coupon['id']) }}" title="Edit Coupon"><i class="fas fa-edit"></i></a>
                          &nbsp;&nbsp;  
                          <a href="javascript:void(0)" class="confirmDelete" name="coupon" record="coupon" recordid="{{ $coupon['id'] }}"
                          {{-- href="{{ url('admin/delete-coupon/'.$coupon['id']) }}" --}} title="Delete Coupon"><i class="fas fa-trash-alt"></i></a> 
                          &nbsp;&nbsp;
                          @if($coupon['status']==1)
                            <a class="updateCouponStatus" id="coupon-{{ $coupon['id'] }}" coupon_id="{{ $coupon['id'] }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                          @else 
                            <a class="updateCouponStatus" id="coupon-{{ $coupon['id'] }}" coupon_id="{{ $coupon['id'] }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                          @endif
                        </td>                    
                    </tr>    
                    @endforeach                  

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>                   
                        <th>Coupon Type</th>
                        <th>Amount Type</th>                    
                        <th>Amount</th>                    
                        <th>Expiry Date</th>                   
                        <th>Actions</th>                       
                    </tr>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection