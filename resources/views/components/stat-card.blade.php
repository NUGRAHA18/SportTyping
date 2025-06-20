{{-- resources/views/components/stat-card.blade.php --}}
@props([
    'icon' => 'fas fa-chart-line',
    'title' => 'Statistic',
    'value' => '0',
    'unit' => '',
    'change' => null,
    'changeType' => 'neutral', // 'positive', 'negative', 'neutral'
    'color' => 'primary', // 'primary', 'success', 'warning', 'danger', 'info'
    'size' => 'default' // 'small', 'default', 'large'
])

@php
    $colorClasses = [
        'primary' => 'stat-card-primary',
        'success' => 'stat-card-success', 
        'warning' => 'stat-card-warning',
        'danger' => 'stat-card-danger',
        'info' => 'stat-card-info'
    ];
    
    $sizeClasses = [
        'small' => 'stat-card-sm',
        'default' => '',
        'large' => 'stat-card-lg'
    ];
    
    $changeClasses = [
        'positive' => 'stat-change-positive',
        'negative' => 'stat-change-negative',
        'neutral' => 'stat-change-neutral'
    ];
@endphp

<div class="stat-card {{ $colorClasses[$color] ?? 'stat-card-primary' }} {{ $sizeClasses[$size] ?? '' }} {{ $attributes->get('class') }}">
    <div class="stat-card-content">
        <div class="stat-header">
            <div class="stat-icon">
                <i class="{{ $icon }}"></i>
            </div>
            @if($change !== null)
                <div class="stat-change {{ $changeClasses[$changeType] ?? 'stat-change-neutral' }}">
                    <i class="fas fa-{{ $changeType === 'positive' ? 'arrow-up' : ($changeType === 'negative' ? 'arrow-down' : 'minus') }}"></i>
                    {{ $change }}{{ $unit ? '%' : '' }}
                </div>
            @endif
        </div>
        
        <div class="stat-body">
            <div class="stat-value">
                {{ $value }}<span class="stat-unit">{{ $unit }}</span>
            </div>
            <div class="stat-title">{{ $title }}</div>
        </div>
        
        @if(isset($slot) && !$slot->isEmpty())
            <div class="stat-footer">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

<style>
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-primary);
    transition: all 0.3s ease;
}

.stat-card-primary::before { background: var(--champion-gradient); }
.stat-card-success::before { background: var(--victory-gradient); }
.stat-card-warning::before { background: var(--medal-gradient); }
.stat-card-danger::before { background: linear-gradient(135deg, var(--accent-danger), #dc2626); }
.stat-card-info::before { background: linear-gradient(135deg, var(--accent-purple), #7c3aed); }

.stat-card-content {
    position: relative;
    z-index: 1;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: var(--accent-primary);
}

.stat-card-primary .stat-icon { background: var(--champion-gradient); }
.stat-card-success .stat-icon { background: var(--victory-gradient); }
.stat-card-warning .stat-icon { background: var(--medal-gradient); }
.stat-card-danger .stat-icon { background: linear-gradient(135deg, var(--accent-danger), #dc2626); }
.stat-card-info .stat-icon { background: linear-gradient(135deg, var(--accent-purple), #7c3aed); }

.stat-change {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.8rem;
    font-weight: 600;
}

.stat-change-positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-success);
}

.stat-change-negative {
    background: rgba(239, 68, 68, 0.1);
    color: var(--accent-danger);
}

.stat-change-neutral {
    background: var(--bg-secondary);
    color: var(--text-secondary);
}

.stat-body {
    margin-bottom: 1rem;
}

.stat-value {
    font-family: var(--font-display);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-unit {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-left: 0.25rem;
}

.stat-title {
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

.stat-footer {
    padding-top: 1rem;
    border-top: 1px solid var(--border-light);
    font-size: 0.85rem;
    color: var(--text-secondary);
}

/* Size variations */
.stat-card-sm {
    padding: 1rem;
}

.stat-card-sm .stat-icon {
    width: 36px;
    height: 36px;
    font-size: 1.2rem;
}

.stat-card-sm .stat-value {
    font-size: 2rem;
}

.stat-card-lg {
    padding: 2rem;
}

.stat-card-lg .stat-icon {
    width: 56px;
    height: 56px;
    font-size: 1.8rem;
}

.stat-card-lg .stat-value {
    font-size: 3rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .stat-card-lg .stat-value {
        font-size: 2.5rem;
    }
}
</style>