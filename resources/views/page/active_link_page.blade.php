@extends('layouts.app')

@section('title', 'Your Active Link - Lucky Game')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
                        <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="display-5 text-primary mb-3">
                    <i class="bi bi-link-45deg"></i> Lucky Game Link
                </h1>
                <p class="lead text-muted">
                    Your unique game link is active
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Info Message -->
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Link Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-link"></i> Your Unique Link
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">

                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-warning me-2"></i>
                            <strong class="me-2">Лінк активний до:</strong>
                            <span>{{ $link->expires_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                        <form method="POST" action="{{ route('link.regenerate', $link->token) }}" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-arrow-clockwise me-2"></i> Regenerate Link
                            </button>
                        </form>

                        <form method="POST" action="{{ route('link.deactivate', $link->token) }}" class="flex-fill">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle me-2"></i> Deactivate Link
                            </button>
                        </form>
                    </div>

                    </div>
                </div>


            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-dice-5"></i> Lucky Game
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        @if(session('number'))
                            <div class="alert alert-info mb-4">
                                <h5 class="alert-heading mb-3">Результат гри</h5>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-dice-6 text-primary me-2 fs-5"></i>
                                    <strong class="me-2">Число:</strong>
                                    <span class="fs-5 fw-bold">{{ session('number') }}</span>
                                </div>

                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi {{ session('result') == 'Win' ? 'bi-trophy text-success' : 'bi-x-circle text-danger' }} me-2 fs-5"></i>
                                    <strong class="me-2">Результат:</strong>
                                    <span class="fs-5 fw-bold {{ session('result') == 'Win' ? 'text-success' : 'text-danger' }}">
                                        {{ session('result') }}
                                    </span>
                                </div>

                                @if(session('result') == 'Win')
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-cash-coin text-success me-2 fs-5"></i>
                                        <strong class="me-2">Сума виграшу:</strong>
                                        <span class="fs-5 fw-bold text-success">{{ number_format(session('amount'), 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <p class="mb-0">Нажми "Imfeelinglucky" щоб згенерувати рандомне число</p>

                            </div>
                        @endif

                        <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                            <form method="POST" action="{{ route('link.play', $link->token) }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                     Imfeelinglucky
                                </button>
                            </form>

                            <a href="{{ route('link.history', $link->token) }}" class="btn btn-info w-100">
                                 History
                            </a>
                        </div>
                    </div>
                </div>
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
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-primary');

        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.remove('btn-primary');
            button.classList.add('btn-outline-primary');
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
