@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Typing Lessons</h2>
    <p>Learn and improve your typing skills with structured lessons.</p>
    
    <div class="row mt-4">
        @if($lessons->count() > 0)
            @foreach($lessons as $lesson)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $lesson->title }}</h5>
                            <span class="badge bg-{{ $lesson->difficulty_level == 'beginner' ? 'success' : ($lesson->difficulty_level == 'intermediate' ? 'warning' : ($lesson->difficulty_level == 'advanced' ? 'danger' : 'primary')) }}">
                                {{ ucfirst($lesson->difficulty_level) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <p>{{ Str::limit($lesson->description ?? 'Learn typing techniques with this structured lesson.', 100) }}</p>
                            <p class="text-muted">
                                <small>
                                    <i class="fas fa-clock"></i> Est. time: {{ $lesson->estimated_completion_time ?? 10 }} min
                                    <br>
                                    <i class="fas fa-star"></i> XP Reward: {{ $lesson->experience_reward }}
                                </small>
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('guest.lessons.show', $lesson) }}" class="btn btn-primary w-100">
                                Start Lesson
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    No lessons available yet. Please check back later.
                </div>
            </div>
        @endif
    </div>

    <div class="mt-5 text-center">
        <p>Want to track your progress and earn badges?</p>
        <div>
            <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        </div>
    </div>
</div>
@endsection