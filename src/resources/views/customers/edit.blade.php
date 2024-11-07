@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Customer Account Management</h4>
                <h6>Update Customer Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('customer.update') }}" id="barber_edit_form"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <input type="hidden" name="customer_id" value="{{ $customer->uniqueid }}">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Name <span style="color: red">*</span></label>
                                <input type="text" name="name" value="{{ $customer->name }}" required>
                            </div>
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Email <span style="color: red">*</span></label>
                                <input type="text" name="email" value="{{ $customer->email }}">
                            </div>
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Number <span style="color: red">*</span></label>
                                <input type="text" name="contact_number" pattern="\d*" title="Please enter only numbers"
                                    value="{{ $customer->contact_number }}" required>
                            </div>
                            @error('contact_number')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('customers/') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
