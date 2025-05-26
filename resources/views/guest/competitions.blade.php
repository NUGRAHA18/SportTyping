@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Typing Competitions</h2>
    <p>Challenge yourself and others in real-time typing competitions.</p>
    
    <div class="row mt-4">
        <!-- Active Competitions -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Active Competitions</h4>
                </div>
                <div class="card-body">
                    @if($activeCompetitions->count() > 0)
                        <div class="list-group">
                            @foreach($activeCompetitions as $competition)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $competition->title }}</h5>
                                        <span class="badge bg-{{ $competition->device_type == 'mobile' ? 'warning' : ($competition->device_type == 'pc' ? 'info' : 'success') }}">
                                            {{ ucfirst($competition->device_type) }}
                                        </span>
                                    </div>
                                    <p class="mb-1">{{ $competition->description ?? 'Test your typing skills in this exciting competition!' }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">Started: {{ $competition->start_time->diffForHumans() }}</small>
                                        <a href="{{ route('guest.competition.show', $competition) }}" class="btn btn-primary btn-sm">
                                            Join Competition
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            No active competitions right now. Check upcoming competitions or check back later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Upcoming Competitions -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Upcoming Competitions</h4>
                </div>
                <div class="card-body">
                    @if($upcomingCompetitions->count() > 0)
                        <div class="list-group">
                            @foreach($upcomingCompetitions as $competition)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $competition->title }}</h5>
                                        <span class="badge bg-{{ $competition->device_type == 'mobile' ? 'warning' : ($competition->device_type == 'pc' ? 'info' : 'success') }}">
                                            {{ ucfirst($competition->device_type) }}
                                        </span>
                                    </div>
                                    <p class="mb-1">{{ $competition->description ?? 'Test your typing skills in this exciting competition!' }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">Starts: {{ $competition->start_time->diffForHumans() }}</small>
                                        <span class="btn btn-outline-secondary btn-sm disabled">
                                            Coming Soon
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            No upcoming competitions scheduled. Check back later.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <p>Want to track your competition results and climb the leaderboards?</p>
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        </div>
    </div>
</div>
@endsection