@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Home View </h4>
                <h6>Manage Home View</h6>
            </div>
            <div class="page-btn">
                @if($home_views->count() < 3)
                <a href="{{ route('home_views.create') }}" class="btn btn-added"><i class="fas fa-plus" style="color: #ffffff;margin-right: 4px;"></i>Add New Home View</a>
                @endif
            </div>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card" >
            <div class="card-body">
                {{-- <div class="table-top">
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
                </div> --}}

                <div class="table-responsive" >

                    <table class="table" id="barber-table">
                        <thead>
                            <tr>
                                <th style="width:200px !important;">Photo</th>
                                <th>Description</th>
                                <th id="action-header">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($home_views as $home_view)
                                <tr>
                                    <td><img src="{{asset('storage/homeviews/'.$home_view->photo)}}" style="width:200px !important;height:100px;"></td>
                                    <td style="white-space: pre-wrap;">{{$home_view->description}}</td>
                                    <td class="action-row">
                                        <a class="me-3" href="{{route('home_views.edit',$home_view->id)}}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>

                                        </a>
                                        {{-- <form action="#" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none;">
                                                <i class="fas fa-trash-alt fa-lg" style="color: #ff0000;"></i>
                                            </button>
                                        </form> --}}
                                    </td>

                                </tr>
                                @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
