@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Barber Absence List</h4>
                <h6>Manage Barbers Absence</h6>
            </div>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#absence_create"><i class="fas fa-plus" style="color: #ffffff;margin-right: 5px;"></i>Add New</a>
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
                                <th>Barber Name</th>
                                <th>Contact Number</th>
                                <th>Absence Date</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absence_details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="productimgname">
                                        <img src="{{ url('storage/barber_photos/'.$detail->barber->barber_photo) }}" alt="img" width="50px" class="rounded" style="height:50px;object-fit:cover">
                                        <span>{{ $detail->barber->barber_name }}</span>
                                    </td>
                                    <td>{{ $detail->barber->contact_number }}</td>
                                    <td>{{ date('d-m-Y',strtotime($detail->day)) }}</td>
                                    <td>{{ $detail->description }}</td>
                                    <td>

                                        <form action="{{ url('/absence/' . $detail->uniqueid) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
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

        <div class="modal fade" id="absence_create" tabindex="-1" aria-labelledby="absence_create" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Barber Absence Form</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('absence') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Barber<span style="color: red"> *</span></label>
                                        <select class="form-select" id="barber_select" name="barber_select" required>
                                            <option value="" selected disabled>Choose Barber</option>

                                            @foreach ($barbers as $barber)
                                                <option value="{{ $barber->uniqueid }}">{{ $barber->barber_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="text-danger form-control-feedback">
                                            {{ $errors->first('barber_id') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Date<span style="color: red"> *</span></label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            title="Please enter only numbers" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Leave Time<span style="color: red"></span></label>
                                        <input type="time" name="leave_time" id="leave_time" class="form-control"
                                            title="Please enter only numbers">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-12">
                                    <div class="form-group">
                                        <label>Description<span style="color: red"></span></label>
                                        <input type="text" name="description" id="description" class="form-control"
                                            placeholder="Please write your description" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2" type="submit">Submit</button>
                                <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @endsection
