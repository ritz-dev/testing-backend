@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Customer Account List</h4>
                <h6>Manage Customers</h6>
            </div>
            <div class="page-btn">
                <a href="{{ url('/customers/create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #ffffff;margin-right: 4px;"></i>Add New Customer</a>
            </div> 
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('customer-table','Customer List')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew" id="customer-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->contact_number }}</td>
                                    <td>{{ $customer->created_at }}</td>
                                    <td>
                                        <a class="me-3" href="{{ url('/customers/' . $customer->uniqueid) }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                        </a>

                                        {{-- <a class="me-3 confirm-text" data-id="{{ $barber->uniqueid }}"
                                            href="{{ url('/barbers/' . $barber->uniqueid) }}" >
                                            <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg"
                                                alt="img">
                                        </a> --}}

                                        <form action="{{ route('customer.destroy') }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="customer_id" value="{{ $customer->uniqueid }}">
                                            <button type="submit" style="background: none; border: none;">
                                                <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>

                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
