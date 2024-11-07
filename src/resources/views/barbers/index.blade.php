@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Barber List</h4>
                <h6>Manage Barbers</h6>
            </div>
            @if (auth()->user()->role_id==1)
            <div class="page-btn">
                <a href="{{ url('/barbers/create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #ffffff;margin-right: 5px;"></i>Add New Barber</a>
            </div>
            @endif
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card" >
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" id="pdf-btn" onclick="generatePdf('barber-table','Barber List')"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive" >
                    <table class="table datanew" id="barber-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barber Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                                <th id="action-header">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barbers as $barber)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="productimgname">
                                        <img src="{{ url('storage/barber_photos/'.$barber->barber_photo) }}" alt="img" width="50px" class="rounded" style="height:50px;object-fit:cover">
                                        <span>{{ $barber->barber_name }}</span>
                                    </td>
                                    <td>{{ $barber->email }}</td>
                                    <td>{{ $barber->contact_number }}</td>
                                    <td class="action-row">
                                        @if (auth()->user()->role_id==1 || auth()->user()->role_id==2)
                                            <a class="me-3" href="{{ url('/barbers/' . $barber->uniqueid . '/edit') }}">
                                                <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                            </a>
                                        @endif
                                        @if (auth()->user()->role_id==1 )
                                            <form action="{{ url('/barbers/' . $barber->id).'/delete' }}" method="POST"
                                                class="d-inline-block"
                                                onsubmit="return confirm('Are you sure want to delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: none; border: none;">
                                                    <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>

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
