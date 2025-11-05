@extends('layout.main')
@push('styles')
    <style>
        .table .bg-light {
            background-color: #f5f6f8 !important;
        }

        .table .font-weight-bold {
            font-weight: 600;
        }

        .text-primary {
            font-weight: bold;
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('title', 'HR Records')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-auto" style="max-width: 600px;" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0 text-primary"><i class="fas fa-user-clock"></i> HR Records Dashboard</h4>
            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRecordModal">
                <i class="fas fa-plus-circle"></i> Add Record
            </a>
        </div>

        <!-- Summary Info Boxes -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Current Date</span>
                        <span class="info-box-number">{{ now()->format('l, d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Employees Today</span>
                        <span class="info-box-number">
                            {{ $records->first()->number_of_employees ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Cumulative Work Hours</span>
                        <span class="info-box-number">{{ number_format($cumulativeHours, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Daily Work Records</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Number of Employees</th>
                            <th>Total Work Hours</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $key => $record)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                                <td>{{ $record->number_of_employees }}</td>
                                <td>{{ $record->total_work_hours }}</td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-warning text-white" data-toggle="modal"
                                        data-target="#editRecordModal{{ $record->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('hr.destroy', $record->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this record?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Edit Record Modal -->
                            <div class="modal fade" id="editRecordModal{{ $record->id }}" tabindex="-1"
                                aria-labelledby="editRecordLabel{{ $record->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title" id="editRecordLabel{{ $record->id }}">Edit Record
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form action="{{ route('hr.update', $record->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Date</label>
                                                    {{-- <input type="date" name="date" value="{{ $record->date }}"
                                                        class="form-control" required> --}}
                                                    <input type="date" name="date" value="{{ $record->date }}"
                                                        class="form-control" required max="{{ date('Y-m-d') }}">
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label>Number of Employees</label>
                                                    <input type="number" name="number_of_employees"
                                                        value="{{ $record->number_of_employees }}" class="form-control"
                                                        min="0" required>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label>Total Work Hours</label>
                                                    <input type="number" step="0.01" name="total_work_hours"
                                                        value="{{ $record->total_work_hours }}" class="form-control"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning text-white">Update
                                                    Record</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No HR records available.
                                </td>
                            </tr>
                        @endforelse

                        @if ($records->count())
                            <tr class="bg-light font-weight-bold">
                                <td colspan="2"></td>
                                <td class="text-right pr-2">Cumulative Total Work Hours:</td>
                                <td colspan="2" class="text-primary">
                                    {{ number_format($cumulativeHours, 2) }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>

    </div>
    <!-- Add Record Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addRecordLabel">Add New Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('hr.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Date</label>
                            {{-- <input type="date" name="date" class="form-control" required> --}}
                            <input type="date" name="date" class="form-control" required
                                max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group mt-3">
                            <label>Number of Employees</label>
                            <input type="number" name="number_of_employees" class="form-control" min="0"
                                required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Total Work Hours</label>
                            <input type="number" step="0.01" name="total_work_hours" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> --}}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
