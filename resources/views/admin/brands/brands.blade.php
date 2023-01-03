@extends('layouts.admin_layout.admin_design')
@section('content')
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Brands</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Brands</li>
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
                <h3 class="card-title">DataTable with Brands</h3>
                <a href="{{ url('admin/add-edit-brand') }}" class="btn btn-block btn-success" style="max-width: 150px; float: right;
                 display: inline-block">Add Brand</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="brands" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>                   
                        <th>Actions</th>                    
                        </tr>
                    </thead>
                    <tbody>

                    @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>                    
                        <td>{{ $brand->name }}</td>                        
                        <td>
                          <a href="{{ url('admin/add-edit-brand/'.$brand->id) }}" title="Edit Brand"><i class="fas fa-edit"></i></a>
                          &nbsp;&nbsp;  
                          <a href="javascript:void(0)" class="confirmDelete" name="brand" record="brand" recordid="{{ $brand->id }}"
                          {{-- href="{{ url('admin/delete-brand/'.$brand->id) }}" --}} title="Delete Brand"><i class="fas fa-trash-alt"></i></a> 
                          &nbsp;&nbsp;
                          @if($brand->status==1)
                            <a class="updateBrandStatus" id="brand-{{ $brand->id }}" brand_id="{{ $brand->id }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                          @else 
                            <a class="updateBrandStatus" id="brand-{{ $brand->id }}" brand_id="{{ $brand->id }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                          @endif
                        </td>                    
                    </tr>    
                    @endforeach                  

                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>                    
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