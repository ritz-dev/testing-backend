@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Walk In Management</h4>
                <h6>Add Walk In Customer</h6>
            </div>
        </div>
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('/walk_in') }}" id="barber_create_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
			<div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Name<span style="color: red"> * </span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Phone<span style="color: red"> </span></label>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barber Name<span style="color: red"> *</span></label>
                                <select class="form-select form-control" id="barber" name="barber_id" required>
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
                                <select class="form-select form-control" id="timePeriod" name="start_time_id" required>
                                    {{-- fetch data --}}
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('start_time_id') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Services<span style="color: red"> *</span></label>
                                @foreach ($services as $service)
                                    <div class="checkbox-row" style="display: flex; gap: 20px;">
                                        <input type="checkbox" name="{{ $service->uniqueid }}"
                                            id="{{ $service->uniqueid }}">
                                        <label
                                            style="display: inline-block; vertical-align: middle; margin-top: 5px;">{{ $service->service_name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">Save</button>
                            <a href="{{ url('walk_in/') }}" class="btn btn-cancel">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
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
