@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('guest.competitions') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Competitions
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>{{ $competition->title }}</h3>
            <span class="badge bg-{{ $competition->device_type == 'mobile' ? 'warning' : ($competition->device_type == 'pc' ? 'info' : 'success') }}">
                {{ ucfirst($competition->device_type) }}
            </span>
        </div>
        
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Instructions:</strong> Race against other players by typing the text as quickly and accurately as possible.
            </div>
            
            <div class="race-track mb-4 p-3 border rounded">
                <h5>Race Track</h5>
                <div class="position-relative mb-4" style="height: 200px; background-color: #f0f0f0; border-radius: 5px;">
                    <!-- Player track -->
                    <div class="position-absolute d-flex align-items-center" style="top: 10px; left: 0; right: 0; height: 40px;">
                        <div class="bg-primary text-white rounded px-2 py-1" style="width: 100px;">You</div>
                        <div class="progress ms-2" style="height: 20px; flex-grow: 1;">
                            <div id="playerProgress" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <!-- Bot tracks -->
                    @foreach($bots as $index => $bot)
                        <div class="position-absolute d-flex align-items-center" style="top: {{ 60 + $index * 40 }}px; left: 0; right: 0; height: 40px;">
                            <div class="bg-secondary text-white rounded px-2 py-1" style="width: 100px;">{{ $bot['name'] }}</div>
                            <div class="progress ms-2" style="height: 20px; flex-grow: 1;">
                                <div id="bot{{ $index }}Progress" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="typing-container mb-4">
                <h5>Text to Type</h5>
                <div class="text-to-type mb-3 p-3 border rounded" id="textToType">
                    {{ $competition->text->content }}
                </div>
                
                <div class="mb-3">
                    <label for="typingInput" class="form-label">Type here:</label>
                    <textarea class="form-control" id="typingInput" rows="4" disabled></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="me-3"><strong>WPM:</strong> <span id="currentWpm">0</span></span>
                        <span><strong>Accuracy:</strong> <span id="currentAccuracy">0%</span></span>
                    </div>
                    <button id="startButton" class="btn btn-primary">Start Race</button>
                </div>
            </div>
            
            <div class="results-container d-none" id="resultsContainer">
                <h4>Race Results</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Name</th>
                                <th>WPM</th>
                                <th>Accuracy</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTable">
                            <!-- Results will be inserted here dynamically -->
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <p>Want to save your results and climb the leaderboards?</p>
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
        const playerProgress = document.getElementById('playerProgress');
        const currentWpm = document.getElementById('currentWpm');
        const currentAccuracy = document.getElementById('currentAccuracy');
        const resultsContainer = document.getElementById('resultsContainer');
        const resultsTable = document.getElementById('resultsTable');
        
        // Bot elements
        const botProgressBars = [];
        @foreach($bots as $index => $bot)
            botProgressBars.push(document.getElementById('bot{{ $index }}Progress'));
        @endforeach
        
        // Bot data
        const bots = @json($bots);
        
        let originalText = textToType.textContent.trim();
        let wordCount = originalText.split(/\s+/).length;
        let startTime;
        let raceInterval;
        let raceActive = false;
        let botIntervals = [];
        
        startButton.addEventListener('click', function() {
            if (!raceActive) {
                startRace();
            }
        });
        
        typingInput.addEventListener('input', function() {
            if (raceActive) {
                updateProgress();
            }
        });
        
        function startRace() {
            // Reset
            typingInput.value = '';
            typingInput.disabled = false;
            typingInput.focus();
            playerProgress.style.width = '0%';
            currentWpm.textContent = '0';
            currentAccuracy.textContent = '0%';
            
            // Reset bot progress
            botProgressBars.forEach(bar => {
                bar.style.width = '0%';
            });
            
            startTime = new Date();
            raceActive = true;
            startButton.disabled = true;
            
            // Start bot progress
            startBots();
            
            // Update stats periodically
            raceInterval = setInterval(updateStats, 1000);
        }
        
        function startBots() {
            bots.forEach((bot, index) => {
                const totalTime = (wordCount / bot.typing_speed) * 60 * 1000; // milliseconds to complete
                const progressInterval = 100; // update every 100ms
                const progressStep = 100 / (totalTime / progressInterval);
                
                let progress = 0;
                
                botIntervals[index] = setInterval(() => {
                    progress += progressStep;
                    if (progress >= 100) {
                        progress = 100;
                        clearInterval(botIntervals[index]);
                        
                        // Check if race is still active and this is the first bot to finish
                        if (raceActive && botIntervals.every((interval, i) => i === index || interval === null)) {
                            setTimeout(() => {
                                endRace(false); // Player lost
                            }, 500);
                        }
                    }
                    botProgressBars[index].style.width = progress + '%';
                }, progressInterval);
            });
        }
        
        function updateProgress() {
            const typedText = typingInput.value;
            const originalWords = originalText.split(/\s+/);
            const typedWords = typedText.split(/\s+/);
            const progress = Math.min(100, (typedWords.length / originalWords.length) * 100);
            
            playerProgress.style.width = progress + '%';
            
            // Check if completed
            if (progress >= 100) {
                endRace(true); // Player won
            }
        }
        
        function updateStats() {
            if (!raceActive) return;
            
            const typedText = typingInput.value;
            const elapsedTime = (new Date() - startTime) / 1000; // seconds
            
            // Calculate WPM
            const typedWords = typedText.split(/\s+/).length;
            const wpm = Math.round((typedWords / elapsedTime) * 60);
            
            // Calculate accuracy
            let correctChars = 0;
            const minLength = Math.min(originalText.length, typedText.length);
            
            for (let i = 0; i < minLength; i++) {
                if (originalText[i] === typedText[i]) {
                    correctChars++;
                }
            }
            
            const accuracy = typedText.length > 0 ? Math.round((correctChars / typedText.length) * 100) : 0;
            
            // Update display
            currentWpm.textContent = wpm;
            currentAccuracy.textContent = accuracy + '%';
        }
        
        function endRace(playerWon) {
            // Stop timers
            clearInterval(raceInterval);
            botIntervals.forEach(interval => clearInterval(interval));
            
            // Calculate final stats
            const typedText = typingInput.value;
            const elapsedTime = (new Date() - startTime) / 1000; // seconds
            
            // Calculate WPM
            const typedWords = typedText.split(/\s+/).length;
            const wpm = Math.round((typedWords / elapsedTime) * 60);
            
            // Calculate accuracy
            let correctChars = 0;
            const minLength = Math.min(originalText.length, typedText.length);
            
            for (let i = 0; i < minLength; i++) {
                if (originalText[i] === typedText[i]) {
                    correctChars++;
                }
            }
            
            const accuracy = typedText.length > 0 ? Math.round((correctChars / typedText.length) * 100) : 0;
            
            // Update display
            currentWpm.textContent = wpm;
            currentAccuracy.textContent = accuracy + '%';
            
            // Generate results
            let results = [
                {
                    name: 'You',
                    wpm: wpm,
                    accuracy: accuracy,
                    time: elapsedTime.toFixed(1),
                    isPlayer: true
                }
            ];
            
            // Add bot results
            bots.forEach(bot => {
                results.push({
                    name: bot.name,
                    wpm: bot.typing_speed,
                    accuracy: bot.accuracy,
                    time: (wordCount / bot.typing_speed * 60).toFixed(1),
                    isBot: true
                });
            });
            
            // Sort by WPM (higher is better)
            results.sort((a, b) => b.wpm - a.wpm);
            
            // Generate results table
            resultsTable.innerHTML = '';
            results.forEach((result, index) => {
                const row = document.createElement('tr');
                if (result.isPlayer) {
                    row.classList.add('table-primary');
                }
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${result.name}</td>
                    <td>${result.wpm}</td>
                    <td>${result.accuracy}%</td>
                    <td>${result.time}s</td>
                `;
                
                resultsTable.appendChild(row);
            });
            
            // Show results
            resultsContainer.classList.remove('d-none');
            
            // Disable input
            typingInput.disabled = true;
            raceActive = false;
            startButton.disabled = true;
        }
    });
</script>
@endsection