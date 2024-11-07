@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>View Address</h4>
            </div>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card" >
            <div class="card-body">
                <div class="table-responsive" >

                    <table class="table datanew" id="barber-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                @if (auth()->user()->role_id==1)
                                <th id="action-header">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shopAddresses as $address)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $address->address }}</td>
                                <td>{{ $address->contact_number }}</td>
                                @if (auth()->user()->role_id==1)
                                <td class="action-row">
                                    <a class="me-3" href="{{ url('/shop_addresses/' . $address->uniqueid . '/edit') }}">
                                        <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
