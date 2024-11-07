@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Home View Management</h4>
                <h6>Update Home View</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{route('home_views.update',$home_view->id)}}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 offset-lg-4 col-sm-6 offset-sm-3 col-12">

                            <div class="form-group">
                                <label>Image<span style="color: red"> *</span></label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" name="photo" >
                                    <div class="image-uploads">
                                        <img src="{{asset('storage/homeviews/'.$home_view->photo)}}" width="100%" class="rounded" alt="photo" style="height:100%;object-fit:cover;">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Description <span style="color: red">*</span></label>
                                <textarea name="description" id="description" cols="30" rows="3">{{$home_view->description}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-4">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ route('home_views.index') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
