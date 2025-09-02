@extends('layouts.app')

@section('title', 'Registration - Lucky Game')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="display-4 text-primary mb-3">
                <i class="bi bi-dice-5"></i> Lucky Game
            </h1>
            <p class="lead text-muted">
                Register and get a unique link for the "ImFeelingLucky" game
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}

                @if(session('unique_token'))
                    <hr>
                    <h6 class="alert-heading">Your unique link:</h6>
                    <div class="d-flex align-items-center">
                        <input type="text"
                               class="form-control form-control-sm me-2"
                               value="{{ session('link')}}"
                               readonly
                               id="uniqueLink">
                        <button class="btn btn-outline-success btn-sm"
                                onclick="copyToClipboard()"
                                title="Copy link">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> Link is valid for 7 days
                    </small>
                @endif

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Info Message -->
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Validation Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i> <strong>Error!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Registration Form -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i> Registration form
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i> Username <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Enter your username"
                               required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">
                            <i class="bi bi-telephone"></i> Phone Number <span class="text-danger">*</span>
                        </label>
                        <input type="tel"
                               class="form-control @error('phone_number') is-invalid @enderror"
                               id="phone_number"
                               name="phone_number"
                               value="{{ old('phone_number') }}"
                               placeholder="+380123456789"
                               required>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Enter phone number in the format +380123456789
                        </div>
                        @error('phone_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Register
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4 bg-light">
            <div class="card-body text-center">
                <h6 class="card-title">
                    <i class="bi bi-info-circle text-primary"></i> What happens after registration?
                </h6>
                <p class="card-text small text-muted mb-0">
                    You will get a unique link, which will be valid for 7 days.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function copyToClipboard() {
    const linkInput = document.getElementById('uniqueLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // For mobile devices

    try {
        document.execCommand('copy');

        // Show success feedback
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="bi bi-check"></i>';
        button.classList.remove('btn-outline-success');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-success');
        }, 2000);

    } catch (err) {
        console.error('Error: ', err);
    }
}

// Auto-dismiss alerts after 10 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (alert.querySelector('.btn-close')) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    });
}, 10000);
</script>
@endsection

