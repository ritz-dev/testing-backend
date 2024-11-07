@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Walk In Customers List</h4>
                <h6>Manage Walk In</h6>
            </div>
            <div class="page-btn">
                <a href="{{ url('/walk_in/create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #ffffff;margin-right: 5px;"></i>Add Walk In Customer</a>
            </div>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('walkin-table', 'Walk In List')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew" id="walkin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barber Name</th>
				 <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>Duration</th>
                                <th>Pricing</th>
                                <th>Discount</th>
                                <th>Total Price</th>
                                {{-- @if (auth()->user()->role_id==1 || auth()->user()->role_id==2) --}}
                               	    <th>Action</th>
                                 {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=1; ?>
                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $detail->barber_name }}</td>
					<td>{{$detail->name}}</td>
                                    <td>{{$detail->phone}}</td>
                                    <td>{{ $detail->service_name }}</td>
                                    <td><span class="badges bg-lightgreen">{{ $detail->status }}</span></td>
                                    <td>{{ date('d-M-Y',strtotime($detail->date)) }}</td>
                                    <td>{{ $detail->start_time }}</td>
                                    <td>{{ $detail->duration }}</td>
                                    <td>{{ number_format($detail->pricing) }}</td>
                                    @if($detail->discount_type === 'none')
                                        <td>0</td>
                                        <td>{{number_format($detail->pricing) }}</td>
                                    @else
                                        <td>{{ number_format($detail->discount) }} ({{ $detail->discount_type }})</td>
                                        <td>{{number_format($detail->pricing - $detail->discount)}}</td>
                                    @endif
                                    <td>
                                        @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                                        <a class="me-3" href="{{ url('/walk_in/' . $detail->uniqueid . '/edit') }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                        </a>
                                        @endif
                                        @if (auth()->user()->role_id==1)
                                        <form action="{{ url('/walk_in/' . $detail->uniqueid) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none;">                                                    <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>
                                            </button>
                                        </form>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
