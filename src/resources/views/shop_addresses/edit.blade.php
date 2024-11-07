@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Edit Address</h4>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ url('shop_addresses/' . $shopAddress->uniqueid) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Address<span style="color: red">*</span></label>
                                <input type="text" name="address" value= "{{$shopAddress->address}}" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Number <span style="color: red">*</span></label>
                                <input type="text" name="contact_number" value="{{$shopAddress->contact_number}}" required>
                            </div>
                            
                        </div>
                            <div class="col-lg-12">
                                <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                    Save </button>
                                <a href="{{ route('shop_addresses.index') }}" class="btn btn-cancel">
                                    Cancel
                                </a>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
