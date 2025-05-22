@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('guest.practice') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Practice Texts
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>{{ $text->title }}</h3>
            <span class="badge bg-{{ $text->difficulty_level == 'beginner' ? 'success' : ($text->difficulty_level == 'intermediate' ? 'warning' : ($text->difficulty_level == 'advanced' ? 'danger' : 'primary')) }}">
                {{ ucfirst($text->difficulty_level) }}
            </span>
        </div>
        
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Instructions:</strong> Type the text below as quickly and accurately as you can. Your progress will be tracked in real-time.
            </div>
            
            <div class="typing-container my-4 p-3 border rounded" id="typingContainer">
                <div class="text-to-type mb-4" id="textToType">{{ $text->content }}</div>
                
                <div class="mb-3">
                    <label for="typingInput" class="form-label">Type here:</label>
                    <textarea class="form-control" id="typingInput" rows="4"></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button id="resetButton" class="btn btn-secondary">Reset</button>
                    <button id="startButton" class="btn btn-primary">Start Typing Test</button>
                </div>
            </div>
            
            <div class="results-container d-none" id="resultsContainer">
                <h4>Your Results</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <h2 id="speedResult">0</h2>
                            <p class="mb-0">WPM</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <h2 id="accuracyResult">0%</h2>
                            <p class="mb-0">Accuracy</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center p-3 mb-3">
                            <h2 id="timeResult">0</h2>
                            <p class="mb-0">Seconds</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <p>Want to save your results and track your progress?</p>
                    <div>
                        <a href="{{ route('login') }}" class="btn btn-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textToType = document.getElementById('textToType');
        const typingInput = document.getElementById('typingInput');
        const startButton = document.getElementById('startButton');
        const resetButton = document.getElementById('resetButton');
        const typingContainer = document.getElementById('typingContainer');
        const resultsContainer = document.getElementById('resultsContainer');
        const speedResult = document.getElementById('speedResult');
        const accuracyResult = document.getElementById('accuracyResult');
        const timeResult = document.getElementById('timeResult');
        
        let startTime, endTime, totalTime;
        let timerInterval;
        let testActive = false;
        
        startButton.addEventListener('click', function() {
            if (!testActive) {
                // Start the test
                startTest();
            } else {
                // Complete the test
                completeTest();
            }
        });
        
        resetButton.addEventListener('click', resetTest);
        
        function startTest() {
            typingInput.value = '';
            typingInput.disabled = false;
            typingInput.focus();
            startTime = new Date();
            testActive = true;
            startButton.textContent = 'Complete Test';
            
            // Start timer
            let seconds = 0;
            timerInterval = setInterval(() => {
                seconds++;
                timeResult.textContent = seconds;
            }, 1000);
        }
        
        function completeTest() {
            endTime = new Date();
            totalTime = (endTime - startTime) / 1000; // in seconds
            
            // Calculate WPM and accuracy
            const originalText = textToType.textContent.trim();
            const typedText = typingInput.value.trim();
            
            const wordCount = originalText.split(/\s+/).length;
            const wpm = Math.round((wordCount / totalTime) * 60);
            
            // Simple accuracy calculation
            let correctChars = 0;
            const minLength = Math.min(originalText.length, typedText.length);
            
            for (let i = 0; i < minLength; i++) {
                if (originalText[i] === typedText[i]) {
                    correctChars++;
                }
            }
            
            const accuracy = Math.round((correctChars / originalText.length) * 100);
            
            // Update results
            speedResult.textContent = wpm;
            accuracyResult.textContent = accuracy + '%';
            timeResult.textContent = Math.round(totalTime);
            
            // Show results
            typingContainer.classList.add('d-none');
            resultsContainer.classList.remove('d-none');
            
            // Stop timer
            clearInterval(timerInterval);
            testActive = false;
        }
        
        function resetTest() {
            typingInput.value = '';
            typingInput.disabled = true;
            startButton.textContent = 'Start Typing Test';
            testActive = false;
            
            // Reset timer
            clearInterval(timerInterval);
            timeResult.textContent = '0';
            
            // Reset results
            speedResult.textContent = '0';
            accuracyResult.textContent = '0%';
        }
        
        // Initial setup
        typingInput.disabled = true;
    });
</script>
@endsection