@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <h4>Welcome, {{ Auth::user()->username }}!</h4>
                    <p>This is your personal dashboard where you can track your typing progress.</p>
                    
                    <div class="mt-4">
                        <h5>Your Stats</h5>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3>{{ Auth::user()->profile->typing_speed_avg ?? 0 }}</h3>
                                    <p class="mb-0">WPM</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3>{{ Auth::user()->profile->typing_accuracy_avg ?? 0 }}%</h3>
                                    <p class="mb-0">Accuracy</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <h3>{{ Auth::user()->profile->total_experience ?? 0 }}</h3>
                                    <p class="mb-0">Experience</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Quick Actions</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="{{ route('competitions.index') }}" class="btn btn-primary w-100 mb-2">Join Competition</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('practice.index') }}" class="btn btn-success w-100 mb-2">Practice Typing</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('lessons.index') }}" class="btn btn-info w-100 mb-2">Take Lessons</a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('leaderboards.index') }}" class="btn btn-secondary w-100 mb-2">View Leaderboards</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection