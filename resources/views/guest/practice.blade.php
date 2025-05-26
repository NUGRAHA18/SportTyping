@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Practice Typing</h2>
    <p>Select a text to practice your typing skills.</p>
    
    <div class="row mt-4">
        <!-- Category Filter -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Categories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('guest.practice') }}" class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                            All Categories
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('guest.practice', ['category' => $category->id]) }}" class="list-group-item list-group-item-action {{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }} ({{ $category->texts_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Text List -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5>Typing Texts</h5>
                </div>
                <div class="card-body">
                    @if($texts->count() > 0)
                        <div class="list-group">
                            @foreach($texts as $text)
                                <a href="{{ route('guest.practice.show', $text) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $text->title }}</h5>
                                        <small>{{ ucfirst($text->difficulty_level) }}</small>
                                    </div>
                                    <p class="mb-1">{{ Str::limit($text->content, 100) }}</p>
                                    <small class="text-muted">Category: {{ $text->category->name }}</small>
                                </a>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            {{ $texts->links() }}
                        </div>
                    @else
                        <p>No typing texts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection