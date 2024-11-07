@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Gallery </h4>
                <h6>Manage Gallery</h6>
            </div>
            @if (auth()->user()->role_id==1)
            <div class="page-btn">
                <a href="{{ url('/gallery/create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #ffffff;margin-right: 5px;"></i>Add New Gallery</a>
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
                                <th>Name</th>
                                <th>Image</th>
                                @if (auth()->user()->role_id==1)
                                <th id="action-header">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($galleries as $gallery)
                                <tr>
                                    <td>{{ $gallery->id }}</td>
                                    <td>{{ $gallery->name }}</td>
                                    <td class="productimgname">
                                        <img src="{{ url('storage/gallery/'.$gallery->image) }}" alt="img" width="50px" class="rounded" style="height:50px;object-fit:cover">
                                    </td>
                                    @if (auth()->user()->role_id==1)
                                    <td class="action-row">
                                        <a class="me-3" href="{{ url('gallery/edit/'.$gallery->id)}}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                        </a>
                                        <form action="{{ url('/gallery/delete/' . $gallery->id) }}" method="POST"
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
