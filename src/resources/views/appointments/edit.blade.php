@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Appointment Management for customer {{ $appointment->name }}</h4>
                <h6>Update Appointment Information</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('booking.update') }}" id="appointment_edit_form" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <input type="hidden" name="booking" value="{{ $appointment->booking_unid }}">
                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="barber">With Barber</label>
                            <select name="barber" id="barber" class="form-select">
                                @foreach ($barbers as $barber)
                                    <option value="{{ $barber->uniqueid }}" @if($barber->uniqueid == $appointment->barber_unid) selected @endif>{{ $barber->barber_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ $appointment->date }}">
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <label for="date">Time</label>
                            <select name="time_period" id="timePeriod" class="form-select">
                                @foreach ($time_periods as $time_period)
                                    <option value="{{ $time_period->uniqueid }}" @if($time_period->uniqueid == $appointment->time_unid) selected @endif>{{ $time_period->time_period }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mt-4">
                            <label>Discount Type</label>
                            <select class="form-select form-control" id="discount_type" name="discount_type" required>
                                @php $dis_types = ['none','barber','shop']@endphp
                                @foreach($dis_types as $type)
                                    @if($appointment->discount_type === $type)
                                        <option value="{{$type}}" selected>{{$type}}</option>
                                    @else
                                        <option value="{{$type}}">{{$type}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="text-danger form-control-feedback">
                                {{ $errors->first('discount_type') }}
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mt-4">
                            <label for="contact_name">Discount(MMK)</label>
                            @if($appointment->discount_type === 'none')
                                <input type="text" name="discount" id="discount" class="form-control" disabled>
                            @else
                                <input type="text" name="discount" id="discount" class="form-control" value={{$appointment->discount}} >
                            @endif
                            <div class="text-danger form-control-feedback">
                                {{ $errors->first('discount') }}
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <p class="">Services</p>
                            {{--  @dd($appointment);
                            @foreach ($services as $service)
                                <input type="checkbox" name="services[]" @if($service->uniqueid === $appointment->service_unid) checked @endif value="{{ $service->uniqueid }}" id="{{ $service->service_name }}" >
                                <label for="{{ $service->service_name }}">{{ $service->service_name }}</label>
                                <br />
                            @endforeach  --}}
                            @foreach ($services as $service)
                                <input type="checkbox" name="services[]" value="{{ $service->uniqueid }}" id="{{ $service->uniqueid }}"
                                    {{ in_array($service->uniqueid, $appointment->services) ? 'checked' : '' }}>
                                <label for="{{ $service->uniqueid }}">{{ $service->service_name }}</label>
                                <br />
                            @endforeach   
                        </div>

                        <div class="col-lg-12 mt-4">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Save </button>
                            <a href="{{ url('appointments/') }}" class="btn btn-cancel">
                                Cancel
                            </a>
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
                discount.value = "";
            } else {
                discount.removeAttribute('disabled');
            }
        }

        dis_type.addEventListener("change", disable,false);
    </script>
@endsection
