@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Gallery Management</h4>
                <h6>Update Gallery</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('gallery/' . $galleries->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Name <span style="color: red">*</span></label>
                                <input type="text" name="name" value= "{{$galleries->name}}" required>
                            </div>
                            <div class="form-group">
                                <label>Image<span style="color: red"> *</span></label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" name="image" >
                                    <div class="image-uploads">
                                        <img src="{{ asset('storage/gallery/' . $galleries->image) }}" width="100%" class="rounded" alt="photo" style="height:100%;object-fit:cover;">
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
