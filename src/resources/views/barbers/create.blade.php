@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Barber Management</h4>
                <h6>Add Barber Information</h6>
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
                <form method="POST" action="{{ url('barbers') }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barber Name <span style="color: red">*</span></label>
                                <input type="text" name="barber_name" required>
                            </div>
                            <div class="form-group">
                                <label>Email <span style="color: red">*</span>(Optional)</label>
                                <input type="text" name="email">
                            </div>
                            <div class="form-group">
                                <label>Contact Number <span style="color: red">*</span></label>
                                <input type="text" name="contact_number" pattern="\d*" title="Please enter only numbers" required>
                            </div>
                           <div class="form-group">
                                <label>Working Days<span style="color: red"> *</span></label>
                                @foreach ($working_days as $key=>$day)
                                    <div class="checkbox-row" style="display: flex; gap: 20px;">
                                        <input type="checkbox" class="work-day-checkbox" name="work_days[]" value="{{ $key }}" data-target="{{ $key }}">
                                        <label style="display: inline-block; vertical-align: middle; margin-top: 5px;" for="{{ $day }}">{{ $day }}</label>
                                    </div>
                                    <div class="work-time-fields d-flex d-none" id="work-time-fields-{{ $key }}">
                                        <div class="w-100">
                                            <div class="form-group pe-2">
                                                <label>From <span style="color: red">*</span></label>
                                                <input class="{{ $key }} form-control" type="time" name="from_{{ $key }}" value="10:00">
                                            </div>
                                        </div>
                                        <div class="w-100">
                                            <div class="form-group ps-2">
                                                <label>To<span style="color: red">*</span></label>
                                                <input class="{{ $key }} form-control" type="time" name="to_{{ $key }}" value="20:00">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Password <span style="color: red">*</span></label>
                                <input type="text" name="password"  title="Please enter password" required>
                            </div>
                            {{--  <div class="form-group">
                                <label>Base Salary <span style="color: red">*</span></label>
                                <input type="text" name="base_salary" pattern="\d*" title="Please enter only numbers" required>
                            </div>  --}}
                            {{-- <div class="form-group">
                                <label>Branch <span style="color: red">*</span></label>
                                <input type="text" name="shop_id" required>
                            </div> --}}

                            <div class="form-group">
                                <label>Branch</label>
                                <select class="form-select form-control" name="shop_id" required>
                                    <option id="1" selected>Yangon</option>
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{$errors->first('shop_id')}}
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Join Date<span style="color: red">*</span></label>
                                <input type="date" name="join_date" class="form-control" required>
                            </div>

                            {{-- <div class="form-group">
                                <label>Commission Rate<span style="color: red">*</span></label>
                                <input type="number" name="c_rate" class="form-control" required>
                            </div> --}}

                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label> Profile Picture <span style="color: red">*</span></label>
                                <div class="image-upload image-upload-new">
                                    <input type="file" name="barber_photo" required>
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
@push('scripts')
    <script>
        $(document).ready(function(){
            $('.work-day-checkbox').change(function(){
                var targetId = $(this).data('target');
                $('#work-time-fields-' + targetId).toggleClass('d-none', !this.checked);
            });
        });
    </script>
@endpush
