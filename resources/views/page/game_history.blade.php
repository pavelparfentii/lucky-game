@extends('layouts.app')

@section('title', 'Game History - Lucky Game')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="display-5 text-primary mb-3">
                    <i class="bi bi-clock-history"></i> Game History
                </h1>
                <p class="lead text-muted">
                    Your Lucky Game results history
                </p>
            </div>

            <!-- Back to game button -->
            <div class="mb-4">
                <a href="{{ route('link.show', $link->token) }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i> Back to Game
                </a>
            </div>

            <!-- History Card -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-list-ol"></i> Your Game History
                    </h4>
                </div>
                <div class="card-body">
                    @if(count($games) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
{{--                                        <th>Date & Time</th>--}}
                                        <th>Число</th>
                                        <th>Результат</th>
                                        <th>Сума</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($games as $game)
                                        <tr class="{{ $game->result == 'Win' ? 'table-success' : '' }}">

                                            <td><strong>{{ $game->number }}</strong></td>
                                            <td>
                                                <span class="badge {{ $game->result == 'Win' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $game->result }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($game->amount > 0)
                                                    <strong class="text-success">{{ number_format($game->amount, 2) }}</strong>
                                                @else
                                                    <span class="text-muted">0.00</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-emoji-smile display-1 text-muted"></i>
                            <p class="mt-3 mb-0">Ви ще не грали.</p>
                            <a href="{{ route('link.show', $link->token) }}" class="btn btn-primary mt-3">
                                 Ifeelinglucky
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
