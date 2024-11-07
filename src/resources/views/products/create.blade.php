@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Product Management</h4>
                <h6>Add Product Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('products/store') }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label>Product Name <span style="color: red">*</span></label>
                                <input type="text" name="product_name" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label>Quantity <span style="color: red">*</span></label>
                                <input type="number" class="form-control" min="0" name="quantity" pattern="\d*" title="Please enter only numbers" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label>Price <span style="color: red">*</span></label>
                                <input type="text" name="price" pattern="\d*" title="Please enter only numbers" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" name="description" placeholder="Enter Your Description"></textarea>
                                <div class="text-danger form-control-feedback">
                                    {{$errors->first('description')}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label> Product Image <span style="color: red">*</span></label>
                                <div class="image-upload">
                                    <input type="file" name="photo" required>
                                    <div class="image-uploads">
                                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg"
                                            alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('products/') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
