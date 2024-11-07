@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Gallery Management</h4>
                <h6>Add Gallery</h6>
            </div>
        </div>

        <div class="card">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ url('gallery/store') }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Image Name <span style="color: red">*</span></label>
                                <input type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Image<span style="color: red">*</span></label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" name="image" required>
                                    <div class="image-uploads">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg"
                                            alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('gallery/') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
