@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Walk In Management</h4>
                <h6>Add Walk In Customer</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ url('/walk_in/' . $wic[0]->uniqueid) }}" id="wic_edit_form"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
			<div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Name<span style="color: red"> </span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{$wic[0]->name}}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Customer Phone<span style="color: red"> </span></label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{$wic[0]->phone}}">
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barber Name<span style="color: red"> *</span></label>
                                <select class="form-select" id="barber" name="barber_id" required>
                                    @foreach ($barbers as $barber)
                                        <option value="{{ $barber->uniqueid }}"
                                            @if ($wic[0]->barber_id == $barber->uniqueid) selected @endif>{{ $barber->barber_name }}
                                        </option>
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
                                <input type="date" name="date" id="date" class="form-control"
                                    value="{{ $wic[0]->date }}"title="Please enter only numbers" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Time<span style="color: red"> *</span></label>
                                <select class="form-select" id="timePeriod" name="start_time_id" required>
                                    @foreach ($time_periods as $time)
                                        <option value="{{ $time->uniqueid }}"
                                            @if ($wic[0]->time_period_id == $time->uniqueid) selected @endif>{{ $time->time_period }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="text-danger form-control-feedback">
                                    {{ $errors->first('start_time_id') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <label>Discount Type</label>
                            <select class="form-select form-control" id="discount_type" name="discount_type" required>
                                @php $dis_types = ['none','barber','shop']@endphp
                                @foreach($dis_types as $type)
                                    @if($wic[0]->discount_type === $type)
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

                        <div class="col-lg-4 col-sm-6 col-12">
                            <label for="contact_name">Discount(MMK)</label>
                            @if($wic[0]->discount_type === 'none')
                                <input type="text" name="discount" id="discount" class="form-control" disabled>
                            @else
                                <input type="text" name="discount" id="discount" class="form-control" value={{$wic[0]->discount}} >
                            @endif
                            <div class="text-danger form-control-feedback">
                                {{ $errors->first('discount') }}
                            </div>
                        </div>
                    </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Services</label>
                                @foreach ($services as $service)
                                    <div class="checkbox-row" style="display: flex; gap: 20px;">
                                        <input type="checkbox" name="{{ $service->uniqueid }}" value="{{ $service->uniqueid }}"
                                            id="{{ $service->uniqueid }}"
                                            @if (isset($wic) && $wic->contains('service_id', $service->uniqueid)) checked @endif>
                                        <label style="display: inline-block; vertical-align: middle; margin-top: 5px;">
                                            {{ $service->service_name }}
                                        </label>
                                    </div>
                                @endforeach
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
