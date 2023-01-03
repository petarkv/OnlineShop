@extends('layouts.admin_layout.admin_design')
@section('content')
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Products</h1>
          </div>         

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
            </ol>
          </div>
        </div>

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
                <h3 class="card-title">DataTable with Products</h3>
                <a href="{{ url('admin/add-edit-product') }}" class="btn btn-block btn-success" style="max-width: 150px; float: right;
                 display: inline-block">Add Product</a>
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="products" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Product Color</th>
                        <th>Product Image</th>
                        <th>Category</th>
                        <th>Section</th>
                        <th>Status</th>                    
                        <th>Actions</th>                    
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($products as $product)                    
                    <tr>
                        <td>{{ $product->id }}</td>                    
                        <td>{{ $product->product_name }}</td>                   
                        <td>{{ $product->product_code }}</td>                   
                        <td>{{ $product->product_color }}</td>                        
                        <td>
                          <?php $product_image_path = "images/product_images/small/".$product->main_image; ?>
                          @if (!empty($product->main_image) && file_exists($product_image_path))
                            <img style="width:100px;" src="{{ asset('images/product_images/small/'.$product->main_image) }}">
                          @else
                            <img style="width:100px;" src="{{ asset('images/product_images/small/no-image.png') }}">
                          @endif                          
                        </td>
                        <td>{{ $product->category->category_name }}</td>
                        <td>{{ $product->section->name }}</td>
                        <td>
                            @if($product->status==1)
                                <a class="updateProductStatus" id="product-{{ $product->id }}" product_id="{{ $product->id }}" 
                                    href="javascript:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                            @else 
                                <a class="updateProductStatus" id="product-{{ $product->id }}" product_id="{{ $product->id }}" 
                                    href="javascript:void(0)"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                            @endif
                        </td>  
                        <td>
                          <a href="{{ url('admin/add-attributes/'.$product->id) }}" title="Add/Edit Attributes"><i class="fas fa-plus-square"></i></a>
                          &nbsp;&nbsp;
                          <a href="{{ url('admin/add-images/'.$product->id) }}" title="Add Images"><i class="fas fa-images"></i></a>
                          &nbsp;&nbsp;
                          <a href="{{ url('admin/add-edit-product/'.$product->id) }}" title="Edit Product"><i class="fas fa-edit"></i></a>
                          &nbsp;&nbsp;  
                          <a href="javascript:void(0)" class="confirmDelete" name="product" record="product" recordid="{{ $product->id }}"
                          {{-- href="{{ url('admin/delete-product/'.$product->id) }}" --}} title="Delete Product"><i class="fas fa-trash-alt"></i></a>  
                        </td>                  
                    </tr>    
                    @endforeach                  

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Product Color</th>
                        <th>Product Image</th>
                        <th>Category</th>
                        <th>Section</th>
                        <th>Status</th>                    
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