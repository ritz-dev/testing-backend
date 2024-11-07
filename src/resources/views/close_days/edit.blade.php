@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Close Day Management</h4>
                <h6>Edit Close Day</h6>
            </div>
        </div>
        <div class="card">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="card-body">
                <form action="{{ route('close-days.update', $closeDay->id) }}" method="post">
                    @csrf
                    @method('PUT')
        
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date<span style="color: red">*</span></label>
                                <input type="date" name="date" value="{{ $closeDay->date }}" required>
                            </div>
                            <div class="form-group">
                                <label>Reason <span style="color: red">*</span></label>
                                <input type="text" name="reason" value="{{ $closeDay->reason }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" id="barber_create_btn" class="btn btn-submit me-2">
                                Update </button>
                            <a href="{{ route('close-days.index') }}" class="btn btn-cancel">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
