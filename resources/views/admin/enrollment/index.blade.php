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
                            <label for="program_id" class="form-label">Select Program <span class="text-danger">*</span></label>

                            <!-- Hidden Select for Form Submission -->
                            <select name="program_id" id="program_id" class="form-control @error('program_id') is-invalid @enderror" required style="display: none;">
                                <option value="">-- Select a Program --</option>
                                @foreach($programs as $program)
                                    <option value="{{ $program->id }}" {{ (isset($selected_program_id) && $selected_program_id == $program->id) || old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->title }} - KES {{ number_format($program->regular_fee, 0) }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Custom Mobile-Friendly Dropdown -->
                            <div class="dropdown-container" id="programDropdownContainer">
                                <button class="form-control dropdown-toggle @error('program_id') is-invalid @enderror"
                                        type="button"
                                        id="programDropdownToggle"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        aria-haspopup="true"
                                        data-bs-auto-close="outside"
                                        style="text-align: left; background: white; border: 1px solid #dee2e6; cursor: pointer;">
                                    <span id="programDropdownLabel">-- Select a Program --</span>
                                    <span class="dropdown-arrow" style="float: right; margin-top: -2px;">
                                        <i class="fas fa-chevron-down"></i>
                                    </span>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end program-dropdown-menu"
                                     id="programDropdownMenu"
                                     aria-labelledby="programDropdownToggle"
                                     style="max-height: 300px; overflow-y: auto; min-width: 100%; width: 100%;">
                                    <div class="dropdown-header">Select a Program</div>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-search" style="padding: 8px; border-bottom: 1px solid #e9ecef;">
                                        <input type="text" class="form-control form-control-sm" id="programSearch" placeholder="Search programs..." style="font-size: 0.85rem;">
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <div class="dropdown-items-container">
                                        @foreach($programs as $program)
                                            <button class="dropdown-item program-option"
                                                    type="button"
                                                    data-program-id="{{ $program->id }}"
                                                    data-program-title="{{ $program->title }}"
                                                    data-program-desc="{{ $program->description }}"
                                                    data-program-duration="{{ $program->duration }}"
                                                    data-program-schedule="{{ $program->schedule }}"
                                                    data-program-fee="{{ number_format($program->regular_fee, 0) }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fw-semibold">{{ $program->title }}</div>
                                                    <div class="fw-bold text-primary">KES {{ number_format($program->regular_fee, 0) }}</div>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @error('program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Program Details Display -->
                            <div id="programDetails" class="mt-3 p-3 bg-light rounded" style="display: none;">
                                <h6 class="fw-bold" id="programTitle"></h6>
                                <p class="mb-1 small" id="programDesc"></p>
                                <div class="d-flex gap-3 small">
                                    <span><i class="fas fa-clock me-1"></i> <span id="programDuration"></span></span>
                                    <span><i class="fas fa-calendar me-1"></i> <span id="programSchedule"></span></span>
                                </div>
                            </div>
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

/* Mobile-Friendly Dropdown Styles */
.dropdown-container {
    position: relative;
    width: 100%;
}

.dropdown-toggle {
    text-align: left;
    background: white;
    border: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    border-radius: 8px;
}

.dropdown-toggle:hover {
    border-color: var(--primary);
    background-color: #fff5f0;
}

.dropdown-toggle.selected {
    border-color: var(--primary);
    background-color: #fff5f0;
    color: var(--primary);
}

.dropdown-toggle:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(234, 28, 77, 0.25);
}

.dropdown-arrow {
    transition: transform 0.2s ease;
}

.dropdown-toggle.show .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    display: none;
    min-width: 100%;
    padding: 0;
    margin: 0;
    font-size: 1rem;
    color: #212529;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 8px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-menu.show {
    display: block;
}

.dropdown-header {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: #6c757d;
    border-bottom: 1px solid #e9ecef;
}

.dropdown-divider {
    height: 1px;
    margin: 0;
    overflow: hidden;
    background-color: #e9ecef;
}

.dropdown-search {
    padding: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.dropdown-search .form-control {
    border-radius: 6px;
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
}

.dropdown-items-container {
    max-height: 300px;
    overflow-y: auto;
}

.program-option {
    width: 100%;
    text-align: left;
    padding: 0.5rem 0.75rem;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f8f9fa;
    font-size: 0.9rem;
}

.program-option:last-child {
    border-bottom: none;
}

.program-option:hover {
    background-color: #f8f9fa;
    color: var(--primary);
}

.program-option:focus {
    outline: none;
    background-color: #e9ecef;
}

    /* Mobile-specific styles */
@media (max-width: 768px) {
    .dropdown-menu {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 95% !important;
        max-width: 400px !important;
        z-index: 99999 !important;
        margin: 0 !important;
        border-radius: 12px !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4) !important;
        max-height: 70vh !important;
        overflow-y: auto !important;
    }

    .dropdown-search {
        position: sticky !important;
        top: 0 !important;
        background: #fff !important;
        z-index: 10 !important;
        border-bottom: 1px solid #e9ecef !important;
    }

    .program-option {
        padding: 0.75rem 1rem !important;
        font-size: 0.9rem !important;
        min-height: 44px !important;
    }

    .program-option .fw-semibold {
        font-size: 0.95rem !important;
    }

    .program-option .text-muted {
        font-size: 0.8rem !important;
    }

    .program-option .text-primary {
        font-size: 0.95rem !important;
        font-weight: 700 !important;
    }

    /* Override academy layout styles that might interfere */
    .dropdown-menu.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .dropdown-container {
        position: relative !important;
        z-index: 1 !important;
    }

    /* Ensure dropdown appears above academy navbar */
    .main-navbar {
        z-index: 1020 !important;
    }
}

/* Tablet styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .dropdown-menu {
        width: 100% !important;
        max-width: 500px !important;
    }
}

/* Desktop styles */
@media (min-width: 1025px) {
    .dropdown-menu {
        width: 100% !important;
        max-width: 600px !important;
    }
}

/* Accessibility improvements */
.dropdown-menu[aria-hidden="true"] {
    display: none;
}

.dropdown-menu[aria-hidden="false"] {
    display: block;
}

/* Scrollbar styling for dropdown */
.dropdown-items-container::-webkit-scrollbar {
    width: 6px;
}

.dropdown-items-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.dropdown-items-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.dropdown-items-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('program_id');
    const programDropdownToggle = document.getElementById('programDropdownToggle');
    const programDropdownLabel = document.getElementById('programDropdownLabel');
    const programDropdownMenu = document.getElementById('programDropdownMenu');
    const programSearch = document.getElementById('programSearch');
    const programOptions = document.querySelectorAll('.program-option');
    const programDetails = document.getElementById('programDetails');

    // Program data from server
    const programs = @json($programs);

    // Initialize selected program if exists
    const initialProgramId = programSelect.value;
    if (initialProgramId) {
        const initialProgram = programs.find(p => p.id == initialProgramId);
        if (initialProgram) {
            programDropdownLabel.textContent = `${initialProgram.title} - KES ${initialProgram.regular_fee.toLocaleString()}`;
            programDropdownToggle.classList.add('selected');
        }
    }

    // Handle dropdown option selection
    programOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();

            const programId = this.getAttribute('data-program-id');
            const programTitle = this.getAttribute('data-program-title');
            const programDesc = this.getAttribute('data-program-desc');
            const programDuration = this.getAttribute('data-program-duration');
            const programSchedule = this.getAttribute('data-program-schedule');
            const programFee = this.getAttribute('data-program-fee');

            // Update hidden select
            programSelect.value = programId;

            // Update dropdown label
            programDropdownLabel.textContent = `${programTitle} - KES ${programFee}`;
            programDropdownToggle.classList.add('selected');

            // Update program details
            document.getElementById('programTitle').textContent = programTitle;
            document.getElementById('programDesc').textContent = programDesc || '';
            document.getElementById('programDuration').textContent = programDuration || '';
            document.getElementById('programSchedule').textContent = programSchedule || '';
            programDetails.style.display = 'block';

            // Close dropdown
            const bootstrapDropdown = bootstrap.Dropdown.getInstance(programDropdownToggle);
            if (bootstrapDropdown) {
                bootstrapDropdown.hide();
            }

            // Trigger change event for any other listeners
            programSelect.dispatchEvent(new Event('change'));
        });
    });

    // Handle search functionality
    if (programSearch) {
        programSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const itemsContainer = document.querySelector('.dropdown-items-container');

            programOptions.forEach(option => {
                const title = option.getAttribute('data-program-title').toLowerCase();
                const desc = option.getAttribute('data-program-desc').toLowerCase();

                if (title.includes(searchTerm) || desc.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    }

    // Handle dropdown events for accessibility
    programDropdownToggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            const bootstrapDropdown = bootstrap.Dropdown.getInstance(this);
            if (bootstrapDropdown) {
                bootstrapDropdown.toggle();
            } else {
                new bootstrap.Dropdown(this).toggle();
            }
        }
    });

    // Handle form submission validation
    const form = programSelect.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!programSelect.value) {
                e.preventDefault();
                programDropdownToggle.focus();
                // Show validation message
                const invalidFeedback = document.querySelector('.dropdown-container .invalid-feedback');
                if (invalidFeedback) {
                    invalidFeedback.style.display = 'block';
                }
            }
        });
    }

    // Update program details when select changes (for compatibility)
    programSelect.addEventListener('change', function() {
        const programId = this.value;

        if (!programId) {
            programDetails.style.display = 'none';
            programDropdownLabel.textContent = '-- Select a Program --';
            programDropdownToggle.classList.remove('selected');
            return;
        }

        const program = programs.find(p => p.id == programId);

        if (program) {
            document.getElementById('programTitle').textContent = program.title;
            document.getElementById('programDesc').textContent = program.description || '';
            document.getElementById('programDuration').textContent = program.duration || '';
            document.getElementById('programSchedule').textContent = program.schedule || '';
            programDetails.style.display = 'block';
        }
    });

    // Mobile-specific improvements
    if (window.innerWidth <= 768) {
        // Adjust dropdown positioning for mobile
        programDropdownMenu.style.position = 'fixed';
        programDropdownMenu.style.left = '50%';
        programDropdownMenu.style.transform = 'translateX(-50%)';
        programDropdownMenu.style.zIndex = '9999';
        programDropdownMenu.style.width = '90%';
        programDropdownMenu.style.maxWidth = '400px';

        // Handle mobile viewport changes
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                programDropdownMenu.style.left = '50%';
                programDropdownMenu.style.transform = 'translateX(-50%)';
                programDropdownMenu.style.width = '90%';
            }
        });
    }
});
</script>
@endpush
@endsection

