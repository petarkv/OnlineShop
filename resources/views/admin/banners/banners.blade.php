@extends('layouts.admin_layout.admin_design')
@section('content')
    
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banners</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Banners</li>
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
                <h3 class="card-title">DataTable with Banners</h3>
                <a href="{{ url('admin/add-edit-banner') }}" class="btn btn-block btn-success" style="max-width: 150px; float: right;
                 display: inline-block">Add Banner</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="banners" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      <th>ID</th>
                      <th>Image</th>                   
                      <th>Link</th>                    
                      <th>Title</th>                    
                      <th>Alt</th>                   
                      <th>Actions</th>                    
                      </tr>
                    </thead>
                    <tbody>

                    @foreach ($banners as $banner)
                    <tr>
                        <td>{{ $banner['id'] }}</td>                    
                        <td>
                          <img style="width:180px;" src="{{ asset('images/banner_images/'.$banner['image']) }}">
                        </td>                        
                        <td>{{ $banner['link'] }}</td>                        
                        <td>{{ $banner['title'] }}</td>                        
                        <td>{{ $banner['alt'] }}</td>                        
                        <td>
                          <a href="{{ url('admin/add-edit-banner/'.$banner['id']) }}" title="Edit Banner"><i class="fas fa-edit"></i></a>
                          &nbsp;&nbsp;  
                          <a href="javascript:void(0)" class="confirmDelete" name="banner" record="banner" recordid="{{ $banner['id'] }}"
                          {{-- href="{{ url('admin/delete-banner/'.$banner['id']) }}" --}} title="Delete Banner"><i class="fas fa-trash-alt"></i></a> 
                          &nbsp;&nbsp;
                          @if($banner['status']==1)
                            <a class="updateBannerStatus" id="banner-{{ $banner['id'] }}" banner_id="{{ $banner['id'] }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-on" status="Active"></i></a>
                          @else 
                            <a class="updateBannerStatus" id="banner-{{ $banner['id'] }}" banner_id="{{ $banner['id'] }}" 
                                href="javascript:void(0)"><i class="fas fa-toggle-off" status="Inactive"></i></a>
                          @endif
                        </td>                    
                    </tr>    
                    @endforeach                  

                    </tbody>
                    <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>                   
                      <th>Link</th>                    
                      <th>Title</th>                    
                      <th>Alt</th>                  
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