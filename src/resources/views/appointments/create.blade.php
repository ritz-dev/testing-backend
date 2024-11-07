@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Appointment Management</h4>
                <h6>Add Appointment</h6>
            </div>
        </div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <p class="text-danger">* This option is only for appointments through phone contact.</p>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('booking.store') }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barber Name<span style="color: red"> *</span></label>
                                <select class="form-select" id="barber" name="barber" required>
                                    <option value="" selected disabled>Choose barber</option>
                                    @foreach ($barbers as $barber)
                                        <option value="{{ $barber->uniqueid }}">{{ $barber->barber_name }}</option>
                                    @endforeach
                                </select>

                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('barber_id') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date<span style="color: red"> *</span></label>
                                <input type="date" name="date" id="date" class="form-control" title="Please enter only numbers"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Time<span style="color: red"> *</span></label>
                                <select class="form-select form-control" id="timePeriod" name="time_period" required>
                                    {{-- fetch data --}}
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('start_time_id') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="customer_id">Customer Name</label>
				<select class="form-control" id="customer_id" name="customer_id" required>
                                <option>Select an option...</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="number" name="contact_number" id="contact_number" class="form-control" disabled>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('contact_number') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-12">
                            <div class="form-group">
                                <label>Discount Type</label>
                                <select class="form-select form-control" id="discount_type" name="discount_type" required>
                                    <option value="none">None</option>
                                    <option value="barber">Barber</option>
                                    <option value="shop">Shop</option>
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('discount_type') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-sm-3 col-12">
                            <div class="form-group">
                                <label for="contact_name">Discount</label>
                                <input type="text" name="discount" id="discount" class="form-control" placeholder="Enter discount(MMK)" disabled>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('discount') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Services<span style="color: red"> *</span></label>
                                @foreach ($services as $service)
                                    <div class="checkbox-row" style="display: flex; gap: 20px;">
                                        <input type="checkbox" name="services[]"
                                            id="{{ $service->uniqueid }}" value="{{ $service->uniqueid }}">
                                        <label
                                            style="display: inline-block; vertical-align: middle; margin-top: 5px;" for="{{ $service->uniqueid }}">{{ $service->service_name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">Save</button>
                            <a href="{{ url('appointments/') }}" class="btn btn-cancel">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        let dis_type = document.getElementById('discount_type');
        let discount = document.getElementById('discount');

        function disable() {
            if(this.value === 'none') {
                discount.setAttribute('disabled','');
            } else {
                discount.removeAttribute('disabled');
            }
        }

        dis_type.addEventListener("change", disable,false);
    </script>
@endsection
@push('scripts')
    <script>
$(document).ready(function() {
            $('.select2').select2({
                ajax:{
                    url : '/appointments/customerPaginationAjax',
                    dataType : 'json',
                    delay : 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used

                        return {
                            results: $.map(data,function(item){
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: data.length === 20
                            }
                        };
                    },
                },
                placeholder: 'Select an option...',
                allowClear: false
            });
        });
$('#customer_id').on('change',function(){
            var customerId = $('#customer_id').val();
            //console.log(customerId);

            $.ajax({
                url: '/appointments/customerAjax',
                type: 'GET',
                data: {
                    customerId : customerId,
                },
                success: function(res){
                    $('#contact_number').val(res.phone);
                }
            })
        })
        $('#barber , #date').on('change', function(){
            const selectedBarberId = $('#barber').val();
            const selectedDate = $('#date').val();

        $.ajax({
            url: '/barber/time',
            type: 'GET',
            data: {
                id: selectedBarberId,
                date: selectedDate
            },
            success: function(response) {
                const times = response;
                console.log(response)
                const selectBox = document.getElementById('timePeriod');
                selectBox.innerHTML = ''; // Clear existing options
                for (const id in times) {
                    const option = document.createElement('option');
                    console.log(times[id])
                    option.value = times[id].uniqueid;
                    option.text = times[id].time_period;
                    selectBox.appendChild(option);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching time values:', error);
            }
        });
    });
    </script>
@endpush
