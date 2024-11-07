@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Service Management</h4>
                <h6>Add/Update Service Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('service/update/'.$service->uniqueid) }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Service Name <span style="color: red">*</span></label>
                                <input type="text" name="service_name" value={{$service->service_name}} required>
                            </div>
                            <div class="form-group">
                                <label>Duration(mins) <span style="color: red">*</span></label>
                                <input type="text" name="duration" value='{{$service->duration}}' pattern="\d*" title="Please enter only numbers" required>
                            </div>
                            <div class="form-group">
                                <label>Price <span style="color: red">*</span></label>
                                <input type="text" name="pricing" pattern="\d*" value='{{$service->pricing}}' title="Please enter only numbers" required>
                            </div>
                            <div class="form-group">
                                <label>Description <span style="color: red">*</span></label>
                                <input type="text" name="description" value='{{$service->description}}' required>
                            </div>
                            <div class="form-group">
                                <label>Is it a package?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_package" id="isPackage" {{ $service->is_package ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isPackage">
                                        Yes, it's a package
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('services') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
