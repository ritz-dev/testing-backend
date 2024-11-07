@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Barber Management</h4>
                <h6>Update Barber Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('barbers/' . $barber->uniqueid) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barber Name <span style="color: red">*</span></label>
                                <input type="text" name="barber_name" value= "{{$barber->barber_name}}" required>
                            </div>
                            <div class="form-group">
                                <label>Email <span style="color: red">*</span></label>
                                <input type="text" name="email" value= "{{$barber->email}}" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Number <span style="color: red">*</span></label>
                                <input type="text" name="contact_number" pattern="\d*" title="Please enter only numbers"  value= "{{$barber->contact_number}}"required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">

                            <div class="form-group">
                                <label>Base Salary <span style="color: red">*</span></label>
                                <input type="text" name="base_salary" pattern="\d*" title="Please enter only numbers" value= "{{$barber->base_salary}}" required>
                            </div>
                            {{-- <div class="form-group">
                                <label>Branch <span style="color: red">*</span></label>
                                <input type="text" name="shop_id" required>
                            </div> --}}

                            <div class="form-group">
                                <label>Branch<span style="color: red"> *</span></label>
                                <select class="form-select" name="shop_id" required>
                                    <option id="1" selected>Yangon</option>
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{$errors->first('shop_id')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Join Date<span style="color: red"> *</span></label>
                                <input type="date" name="join_date" class="form-select"  value= "{{$barber->join_date}}" disabled required>
                            </div>

                            <div class="form-group">
                                <label>Commission Rate<span style="color: red"> *</span></label>
                                <input type="number" name="c_rate" class="form-select"  value= "{{$barber->commission_rate}}" required>
                            </div>

                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label> Profile Picture <span style="color: red"> *</span></label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" name="barber_photo" >
                                    <div class="image-uploads">
                                        {{-- <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/upload.svg"
                                            alt="img"> --}}
                                        <img src="{{ asset('storage/barber_photos/' . $barber->barber_photo) }}" width="100%" class="rounded" alt="photo" style="height:100%;object-fit:cover;">

                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('barbers/') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
