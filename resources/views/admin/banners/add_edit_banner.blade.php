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

            <form name="bannerForm" id="bannerForm" 
            @if (empty($banner['id']))
                action="{{ url('admin/add-edit-banner') }}"
            @else
                action="{{ url('admin/add-edit-banner/'.$banner['id']) }}"
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
                            <div class="form-group">
                                <label for="image">Image</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" id="image"
                                        @if (!empty($banner['image']))
                                        value="{{ $banner['image'] }}"
                                        @endif
                                        >
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>                            
                                </div>

                                @if (!empty($banner['image']))
                                    <div><img style="height: 100px; width: 440px; margin-top: 5px;" 
                                    src="{{ asset('images/banner_images/'.$banner['image']) }}">&nbsp;&nbsp;
                                    <input type="hidden" name="current_banner" value="{{ $banner['image'] }}">
                                    <a class="confirmDelete" href="javascript:void(0)" record="banner-image" recordid="{{ $banner['id'] }}" 
                                    {{-- href="{{ url('admin/delete-banner-image/'.$banner['id']) }}" --}}>Delete Image</a></div>
                                @else
                                    {{-- <div style="height: 100px;"><img style="width: 100px;" src="{{ asset('images/banner_images/no-image.png') }}"></div> --}}
                                @endif
                                
                                <div>(Recommended Image Size: Width:1140px, Height:340px)</div>                            
                            </div>
                        
                            <div class="form-group">
                                <label for="link">Link</label>
                                <input type="text" class="form-control" name="link" id="link" 
                                placeholder="Enter Link" @if (!empty($banner['link'])) 
                                value="{{ $banner['link'] }}" @else value="{{ old('link') }}" @endif>
                            </div>                        
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" 
                                placeholder="Enter Title" @if (!empty($banner['title'])) 
                                value="{{ $banner['title'] }}" @else value="{{ old('title') }}" @endif>
                            </div>
                            
                            <div class="form-group" @if (!empty($banner['image'])) style="margin-top: 145px;" @else style="margin-top: 40px;" @endif>
                                <label for="alt">Alternate Text</label>
                                <input type="text" class="form-control" name="alt" id="alt" 
                                placeholder="Enter Alt Text" @if (!empty($banner['alt'])) 
                                value="{{ $banner['alt'] }}" @else value="{{ old('alt') }}" @endif>
                            </div>                        
                        </div>
                        <!-- /.col -->            
                    </div>
                </div>
                    <!-- /.row -->
            </div>
                <!-- /.card-body -->
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