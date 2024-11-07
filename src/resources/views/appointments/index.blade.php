@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Appointments List</h4>
                <h6>Manage Appointments</h6>
            </div>
            {{-- <div class="page-btn">
                <a href="{{ url('/barbers/create') }}" class="btn btn-added"><img
                        src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/plus.svg" alt="img"
                        class="me-2">Add New Appointment</a>
            </div> --}}
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
               <form>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->start_date }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->end_date }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">Search</label>
                                <button type="submit" class="btn btn-primary">Search</button>

                                <a href="{{route('booking.index')}}"><button type="button" class="btn btn-danger">Clear</button></a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a class="btn btn-searchset">
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('appointment-table','Appointment List')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew" id="appointment-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Booking ID</th>
                                <th>Change status</th>
                                <th>Status</th>
                                <th>Booking Type</th>
                                <th>Date</th>
                                <th>Barber name</th>
                                <th>Customer name</th>
                                <th>Customer phone</th>
                                <th>Services</th>
                                <th>Duration</th>
                                <th>Pricing(MMK)</th>
                                <th>Discount(MMK)</th>
                                <th>Total Price(MMK)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $appointment->booking_unid }}</td>
                                    <td>
                                        <form action="">
                                            <select name="booking_status" id="booking{{ $appointment->booking_unid }}"  class="form-select bookingStatus" @if ($appointment->status === 'complete') disabled @endif>
                                                <option value="complete" @if ($appointment->status === 'complete') selected @endif>
                                                    complete</option>
                                                <option value="cancel" @if ($appointment->status === 'cancel') selected @endif>
                                                    cancel</option>
                                                <option value="confirm" @if ($appointment->status === 'confirm') selected @endif>
                                                    confirm</option>
                                                <option value="active" @if ($appointment->status === 'active') selected @endif>
                                                    active</option>
                                            </select>
                                            <input type="hidden" name="booking_unid"
                                                value="{{ $appointment->booking_unid }}">
                                        </form>
                                    </td>
                                    <td><span id="{{ $appointment->booking_unid }}"
                                            class="badges @if ($appointment->status === 'complete') bg-lightgreen text-white @elseif($appointment->status === 'cancel') bg-warning text-white @elseif($appointment->status === 'decline') bg-danger text-white @elseif($appointment->status === 'active') bg-primary text-white @endif">{{ $appointment->status }}</span>
                                    </td>
                                    <td><span class="badges @if ($appointment->type === "by_customer") bg-dark @else bg-secondary @endif">{{ join(" ", explode("_" ,$appointment->type)) }}</span></td>
                                    <td>{{date('d-M-Y', strtotime($appointment->date)) }}</td>
                                    <td>{{ $appointment->barber_name }}{{$appointment->selected != 1 ? "(Any)" : ""}}</td>
                                    <td>{{ $appointment->type === "by_customer" ? $appointment->name : $appointment->contact_name }}</td>
                                    <td>{{ $appointment->type === "by_customer" ? $appointment->contact_number : $appointment->contact_phone }}</td>
                                    <td>{{ $appointment->service_name }}</td>
                                    <td>{{ $appointment->time_period }}-{{ $appointment->end_time }}({{ $appointment->duration }}min)
                                    </td>                                    <td>{{ number_format($appointment->amount) }}</td>
                                    @if($appointment->discount_type === 'none')
                                        <td>0</td>
                                        <td>{{number_format($appointment->amount) }}</td>
                                    @else
                                        <td>{{ number_format($appointment->discount) }} ({{ $appointment->discount_type }})</td>
                                        {{--  <td>{{number_format($appointment->discount)}}</td>  --}}
                                        <td>{{number_format($appointment->amount - $appointment->discount)}}</td>
                                    @endif
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                                            <a href="{{ url('appointments/edit/'. $appointment->booking_unid) }}" class="me-2">
                                                <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                            </a>
                                            @endif
                                            @if (auth()->user()->role_id==1)
                                            <form action="{{ route('booking.delete') }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" name="booking_id" value="{{ $appointment->booking_unid }}">
                                                <button class="text-danger" style="border: none; background: none;" onclick="return confirm('Are you sure you want delete this appointment?')">
                                                    <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
