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

            <form name="categoryForm" id="categoryForm" 
            @if (empty($categorydata['id']))
                action="{{ url('admin/add-edit-category') }}"
            @else
                action="{{ url('admin/add-edit-category/'.$categorydata['id']) }}"
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
                            <label for="category_name">Category Name</label>
                            <input type="text" class="form-control" name="category_name" id="category_name" 
                            placeholder="Enter Category Name" @if (!empty($categorydata['category_name'])) 
                            value="{{ $categorydata['category_name'] }}" @else value="{{ old('category_name') }}" @endif>
                        </div>

                        <div id="appendCategoriesLevel">
                            @include('admin.categories.append_categories_level')
                        </div>

                    </div>

                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Section</label>
                            <select name="section_id" id="section_id" class="form-control select2" style="width: 100%;">
                            <option value="">--- Select ---</option>
                            @foreach ($getSections as $section)
                            <option value="{{ $section->id }}" @if(!empty($categorydata['section_id']) && 
                                $categorydata['section_id']==$section->id) selected @endif>{{ $section->name }}</option>
                            {{-- <option value="{{ $section->id }}">{{ $section->name }}</option> --}}
                            @endforeach                    
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="exampleInputFile">Category Image</label>
                            <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="category_image" id="category_image">
                                <label class="custom-file-label" for="category_image">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text" id="">Upload</span>
                            </div>                            
                            </div>
                            @if (!empty($categorydata['category_image']))
                                <div style="height: 100px;"><img style="height: 100px; width: 100px; margin-top: 5px;" 
                                src="{{ asset('images/category_images/'.$categorydata['category_image']) }}">&nbsp;&nbsp;
                                <a class="confirmDelete" href="javascript:void(0)" record="category-image" recordid="{{ $categorydata['id'] }}" 
                                {{-- href="{{ url('admin/delete-category-image/'.$categorydata['id']) }}" --}}>Delete Image</a></div>
                            @else
                            <div style="height: 100px;"><img style="width: 100px;" src="{{ asset('images/category_images/no-image.png') }}"></div>
                            @endif
                        </div>
                    </div>             
                    </div>
                    <!-- /.row -->
                    
                    <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="category_discount">Category Discount</label>
                            <input type="text" class="form-control" id="category_discount" name="category_discount" 
                            placeholder="Enter Category Discount" @if (!empty($categorydata['category_discount'])) 
                            value="{{ $categorydata['category_discount'] }}" @else value="{{ old('category_discount') }}" @endif>
                        </div>

                        <div class="form-group">
                            <label for="category_description">Category Description</label>
                            <textarea class="form-control" rows="3" name="category_description" id="category_description" 
                            placeholder="Enter ...">@if (!empty($categorydata['description'])) 
                            {{ $categorydata['description'] }} @else {{ old('description') }} @endif</textarea>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="category_url">Category URL</label>
                            <input type="text" class="form-control" id="category_url" name="category_url" 
                            placeholder="Enter Category URL" @if (!empty($categorydata['url'])) 
                            value="{{ $categorydata['url'] }}" @else value="{{ old('url') }}" @endif>
                        </div>

                        <div class="form-group">
                            <label for="category_meta_title">Meta Title</label>
                            <textarea class="form-control" rows="3" name="category_meta_title" id="category_meta_title" 
                            placeholder="Enter ...">@if (!empty($categorydata['meta_title'])) 
                            {{ $categorydata['meta_title'] }} @else {{ old('meta_title') }} @endif</textarea>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6">                    
                        <div class="form-group">
                            <label for="category_meta_description">Meta Description</label>
                            <textarea class="form-control" rows="3" name="category_meta_description" id="category_meta_description" 
                            placeholder="Enter ...">@if (!empty($categorydata['meta_description'])) 
                            {{ $categorydata['meta_description'] }} @else {{ old('meta_description') }} @endif</textarea>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label for="category_meta_keywords">Meta Keywords</label>
                            <textarea class="form-control" rows="3" name="category_meta_keywords" id="category_meta_keywords"
                            placeholder="Enter ...">@if (!empty($categorydata['meta_keywords'])) 
                            {{ $categorydata['meta_keywords'] }} @else {{ old('meta_keywords') }} @endif</textarea>
                        </div>
                    </div>
                    <!-- /.col -->
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