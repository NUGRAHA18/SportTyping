@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      {{-- Dashboard panel --}}
      <div class="card" style="
        background: var(--panel-dark);
        border: none;
        border-radius: .75rem;
      ">
        <div class="card-header" style="
          background: transparent;
          border-bottom: 1px solid rgba(255,255,255,0.1);
        ">
          <span class="text-light">{{ __('Dashboard') }}</span>
        </div>

        <div class="card-body">
          <h4>
            Welcome, 
            <span style="color: var(--accent-green);">
              {{ Auth::user()->username }}
            </span>!
          </h4>
          <p class="text-muted">
            This is your personal dashboard where you can track your typing progress.
          </p>

          {{-- Your Stats --}}
          <div class="mt-4">
            <h5 class="text-light">Your Stats</h5>
            <div class="row mt-3">
              <div class="col-md-4">
                <div class="feature-card">
                  <h3>{{ Auth::user()->profile->typing_speed_avg ?? 0 }}</h3>
                  <p class="mb-0 text-muted">WPM</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="feature-card">
                  <h3>{{ Auth::user()->profile->typing_accuracy_avg ?? 0 }}%</h3>
                  <p class="mb-0 text-muted">Accuracy</p>
                </div>
              </div>
              <div class="col-md-4">
                <div class="feature-card">
                  <h3>{{ Auth::user()->profile->total_experience ?? 0 }}</h3>
                  <p class="mb-0 text-muted">Experience</p>
                </div>
              </div>
            </div>
          </div>

          {{-- Quick Actions --}}
          <div class="mt-4">
            <h5 class="text-light">Quick Actions</h5>
            <div class="row mt-3 g-2">
              <div class="col-6">
                <a href="{{ route('competitions.index') }}" 
                   class="btn btn-neon w-100">
                  Join Competition
                </a>
              </div>
              <div class="col-6">
                <a href="{{ route('practice.index') }}" 
                   class="btn btn-neon w-100">
                  Practice Typing
                </a>
              </div>
              <div class="col-6">
                <a href="{{ route('lessons.index') }}" 
                   class="btn btn-neon w-100">
                  Take Lessons
                </a>
              </div>
              <div class="col-6">
                <a href="{{ route('leaderboards.index') }}" 
                   class="btn btn-neon w-100">
                  View Leaderboards
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
