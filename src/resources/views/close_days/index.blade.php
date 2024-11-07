@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>View Close Days</h4>
                <h6>Manage Close Days</h6>
            </div>
            <a href="{{ route('close-days.create') }}" class="btn btn-primary mb-2">Add Close Day</a>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew" id="customer-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($closeDays as $closeDay)
                                <tr>
                                    <td>{{ $closeDay->date }}</td>
                                    <td>{{ $closeDay->reason }}</td>
                                    <td>
                                        <a class="me-3" href="{{ route('close-days.edit', $closeDay->id) }}">
                                            <i class="far fa-edit fa-lg" style="color: #ffbb00;"></i>
                                        </a>
                                        <form action="{{ route('close-days.destroy', $closeDay->id) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure want to delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" value="{{ $closeDay->id }}">
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
