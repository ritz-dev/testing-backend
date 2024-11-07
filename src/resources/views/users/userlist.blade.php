@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User List</h4>
                <h6>Manage Users</h6>
            </div>
            <div class="page-btn">
                <a href="{{ url('/add-user') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #fcfcfc;"></i>Add New User</a>
            </div>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
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
                            <li> <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><i class="far fa-file-pdf fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><i class="far fa-file-excel fa-lg"></i></a></li>
                            <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><i class="fas fa-print fa-lg"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @if (auth()->user()->id === $user->id || auth()->user()->role_id == 1)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role_id == 1)
                                                Admin
                                            @elseif($user->role_id == 2)
                                                Staff
                                            @else
                                                Barber
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <a class="me-3" href="{{ url('user-edit/' . $user->id) }}">
                                                <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                            </a>
                                            @if (auth()->user()->role_id == 1)
                                                <form action="{{ url('user-delete/'. $user->id) }}" method="POST" class="d-inline-block"
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
                                @endif
                            @endforeach         

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
