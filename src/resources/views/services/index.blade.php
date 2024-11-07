@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Service List</h4>
                <h6>Manage Services</h6>
            </div>
            @if (auth()->user()->role_id==1)
            <div class="page-btn">
                <a href="{{ url('service/create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #fcfcfc;"></i>Add New Service</a>
            </div>
            @endif
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" onclick="generatePdf('services-table','Services List')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew" id="services-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Service Name</th>
                                <th>Duration</th>
                                <th>Pricing</th>
                                <th>Description</th>
                                @if (auth()->user()->role_id==1)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $service->service_name }}</td>
                                    <td>{{ $service->duration }} min</td>
                                    <td>{{ number_format($service->pricing) }} MMK</td>
                                    <td>{{ $service->description }}</td>
                                    @if (auth()->user()->role_id==1)
                                    <td>
                                        <a class="me-3" href="{{ url('service/edit/' . $service->uniqueid) }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                        </a>

                                        {{-- <a class="me-3 confirm-text" data-id="{{ $service->uniqueid }}"
                                            href="{{ url('/barbers/' . $service->uniqueid) }}" >
                                            <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/delete.svg"
                                                alt="img">
                                        </a> --}}

                                        <form action="{{ url('service/delete/' . $service->uniqueid) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none;">
                                                <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>

                                            </button>
                                        </form>

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
