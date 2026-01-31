@extends('layouts.academy')

@section('title', 'Enroll in Program - Vipers Academy')

@section('content')
<div class="container py-5">
    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Data Captured Successfully!</strong>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle me-2" style="font-size: 1.5rem;"></i>
            <div>
                <strong>Error!</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Enroll in Vipers Academy Program</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('enrol.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="residence" class="form-label">Residence</label>
                            <input type="text" name="residence" id="residence" class="form-control @error('residence') is-invalid @enderror" value="{{ old('residence') }}" required>
                            @error('residence')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="learning_option" class="form-label">Learning Option</label>
                            <select name="learning_option" id="learning_option" class="form-control @error('learning_option') is-invalid @enderror" required>
                                <option value="">Select learning option</option>
                                <option value="Online" {{ old('learning_option') == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Physical" {{ old('learning_option') == 'Physical' ? 'selected' : '' }}>Physical</option>
                            </select>
                            @error('learning_option')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="program_id" class="form-label">Program</label>
                            <select name="program_id" id="program_id" class="form-control @error('program_id') is-invalid @enderror" required>
                                <option value="">Select a program</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ (isset($selected_program_id) && $selected_program_id == $program->id) || old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->title }} ({{ $program->age_group }})
                                    </option>
                                @endforeach
                            </select>
                            @error('program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Enroll Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
}

.alert-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.alert-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.alert .btn-close {
    filter: brightness(0) invert(1);
}

.card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.card-body {
    padding: 2rem;
}

.form-control {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 0.2rem rgba(234, 28, 77, 0.25);
}

.btn-primary {
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 8px;
}

.invalid-feedback {
    font-size: 0.875rem;
}
</style>
@endsection
