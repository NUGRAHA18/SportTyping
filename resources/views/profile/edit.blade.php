@extends('layouts.app')

@section('content')
<div class="profile-edit-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="back-navigation">
                    <a href="{{ route('profile.show') }}" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i>
                        Back to Profile
                    </a>
                </div>
                <h1 class="page-title">
                    <i class="fas fa-user-edit"></i>
                    Edit Profile
                </h1>
                <p class="page-subtitle">
                    Customize your profile information and preferences
                </p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
            @csrf
            @method('PUT')
            
            <div class="edit-content">
                <div class="content-grid">
                    <!-- Left Column -->
                    <div class="left-column">
                        <!-- Avatar & Basic Info -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-user-circle"></i>
                                Profile Picture & Basic Info
                            </h3>
                            
                            <div class="avatar-edit">
                                <div class="avatar-preview">
                                    <div class="current-avatar">
                                        @if($user->profile && $user->profile->avatar)
                                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->username }}" id="avatar-preview">
                                        @else
                                            <div class="avatar-placeholder" id="avatar-preview">
                                                {{ strtoupper(substr($user->username, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="avatar-actions">
                                        <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;">
                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('avatar-input').click()">
                                            <i class="fas fa-camera"></i>
                                            Change Photo
                                        </button>
                                        @if($user->profile && $user->profile->avatar)
                                        <button type="button" class="btn btn-outline-danger" onclick="removeAvatar()">
                                            <i class="fas fa-trash"></i>
                                            Remove
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="avatar-guidelines">
                                    <h4>Photo Guidelines:</h4>
                                    <ul>
                                        <li>Use a clear, recent photo of yourself</li>
                                        <li>Square format works best (1:1 ratio)</li>
                                        <li>Minimum size: 200x200 pixels</li>
                                        <li>Maximum file size: 2MB</li>
                                        <li>Supported formats: JPG, PNG, GIF</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="basic-info-form">
                                <div class="form-group">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-user"></i>
                                        Username
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="{{ old('username', $user->username) }}" required>
                                    <div class="form-help">Your unique identifier on SportTyping</div>
                                    @error('username')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i>
                                        Email Address
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                    <div class="form-help">Used for notifications and account recovery</div>
                                    @error('email')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-id-badge"></i>
                                        Profile Title
                                    </label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title', $user->profile?->title) }}" placeholder="e.g., Speed Typing Champion">
                                    <div class="form-help">A short description that appears under your name</div>
                                    @error('title')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Personal Information
                            </h3>
                            
                            <div class="personal-info-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="{{ old('first_name', $user->profile?->first_name) }}">
                                        @error('first_name')
                                        <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="{{ old('last_name', $user->profile?->last_name) }}">
                                        @error('last_name')
                                        <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="country" class="form-label">
                                            <i class="fas fa-globe"></i>
                                            Country
                                        </label>
                                        <select class="form-control" id="country" name="country">
                                            <option value="">Select Country</option>
                                            <option value="ID" {{ old('country', $user->profile?->country) == 'ID' ? 'selected' : '' }}>Indonesia</option>
                                            <option value="MY" {{ old('country', $user->profile?->country) == 'MY' ? 'selected' : '' }}>Malaysia</option>
                                            <option value="SG" {{ old('country', $user->profile?->country) == 'SG' ? 'selected' : '' }}>Singapore</option>
                                            <option value="TH" {{ old('country', $user->profile?->country) == 'TH' ? 'selected' : '' }}>Thailand</option>
                                            <option value="PH" {{ old('country', $user->profile?->country) == 'PH' ? 'selected' : '' }}>Philippines</option>
                                            <option value="VN" {{ old('country', $user->profile?->country) == 'VN' ? 'selected' : '' }}>Vietnam</option>
                                            <option value="US" {{ old('country', $user->profile?->country) == 'US' ? 'selected' : '' }}>United States</option>
                                            <option value="GB" {{ old('country', $user->profile?->country) == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                            <option value="AU" {{ old('country', $user->profile?->country) == 'AU' ? 'selected' : '' }}>Australia</option>
                                            <option value="CA" {{ old('country', $user->profile?->country) == 'CA' ? 'selected' : '' }}>Canada</option>
                                        </select>
                                        @error('country')
                                        <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="timezone" class="form-label">
                                            <i class="fas fa-clock"></i>
                                            Timezone
                                        </label>
                                        <select class="form-control" id="timezone" name="timezone">
                                            <option value="">Select Timezone</option>
                                            <option value="Asia/Jakarta" {{ old('timezone', $user->profile?->timezone) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                            <option value="Asia/Makassar" {{ old('timezone', $user->profile?->timezone) == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                            <option value="Asia/Jayapura" {{ old('timezone', $user->profile?->timezone) == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                            <option value="Asia/Kuala_Lumpur" {{ old('timezone', $user->profile?->timezone) == 'Asia/Kuala_Lumpur' ? 'selected' : '' }}>Asia/Kuala_Lumpur</option>
                                            <option value="Asia/Singapore" {{ old('timezone', $user->profile?->timezone) == 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                                            <option value="UTC" {{ old('timezone', $user->profile?->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                        </select>
                                        @error('timezone')
                                        <div class="form-error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="bio" class="form-label">
                                        <i class="fas fa-align-left"></i>
                                        Bio
                                    </label>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" 
                                              placeholder="Tell us about yourself, your typing journey, goals, or anything interesting!">{{ old('bio', $user->profile?->bio) }}</textarea>
                                    <div class="form-help">
                                        <span class="char-count">
                                            <span id="bio-count">{{ strlen($user->profile?->bio ?? '') }}</span>/500 characters
                                        </span>
                                    </div>
                                    @error('bio')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Typing Preferences -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-keyboard"></i>
                                Typing Preferences
                            </h3>
                            
                            <div class="typing-preferences-form">
                                <div class="form-group">
                                    <label for="keyboard_layout" class="form-label">
                                        <i class="fas fa-th"></i>
                                        Keyboard Layout
                                    </label>
                                    <select class="form-control" id="keyboard_layout" name="keyboard_layout">
                                        <option value="qwerty" {{ old('keyboard_layout', $user->profile?->keyboard_layout) == 'qwerty' ? 'selected' : '' }}>QWERTY</option>
                                        <option value="dvorak" {{ old('keyboard_layout', $user->profile?->keyboard_layout) == 'dvorak' ? 'selected' : '' }}>Dvorak</option>
                                        <option value="colemak" {{ old('keyboard_layout', $user->profile?->keyboard_layout) == 'colemak' ? 'selected' : '' }}>Colemak</option>
                                        <option value="azerty" {{ old('keyboard_layout', $user->profile?->keyboard_layout) == 'azerty' ? 'selected' : '' }}>AZERTY</option>
                                    </select>
                                    @error('keyboard_layout')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="preferred_language" class="form-label">
                                        <i class="fas fa-language"></i>
                                        Preferred Language for Texts
                                    </label>
                                    <select class="form-control" id="preferred_language" name="preferred_language">
                                        <option value="english" {{ old('preferred_language', $user->profile?->preferred_language) == 'english' ? 'selected' : '' }}>English</option>
                                        <option value="indonesian" {{ old('preferred_language', $user->profile?->preferred_language) == 'indonesian' ? 'selected' : '' }}>Indonesian</option>
                                        <option value="both" {{ old('preferred_language', $user->profile?->preferred_language) == 'both' ? 'selected' : '' }}>Both</option>
                                    </select>
                                    @error('preferred_language')
                                    <div class="form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Typing Goals</label>
                                    <div class="goals-grid">
                                        <div class="goal-item">
                                            <label for="target_wpm" class="goal-label">Target WPM</label>
                                            <input type="number" class="form-control" id="target_wpm" name="target_wpm" 
                                                   value="{{ old('target_wpm', $user->profile?->target_wpm) }}" min="10" max="200">
                                        </div>
                                        <div class="goal-item">
                                            <label for="target_accuracy" class="goal-label">Target Accuracy (%)</label>
                                            <input type="number" class="form-control" id="target_accuracy" name="target_accuracy" 
                                                   value="{{ old('target_accuracy', $user->profile?->target_accuracy) }}" min="50" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="right-column">
                        <!-- Privacy Settings -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-shield-alt"></i>
                                Privacy Settings
                            </h3>
                            
                            <div class="privacy-settings">
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Profile Visibility</div>
                                        <div class="setting-description">Who can view your profile and statistics</div>
                                    </div>
                                    <div class="setting-control">
                                        <select class="form-control" name="profile_visibility">
                                            <option value="public" {{ old('profile_visibility', $user->profile?->profile_visibility) == 'public' ? 'selected' : '' }}>Public</option>
                                            <option value="friends" {{ old('profile_visibility', $user->profile?->profile_visibility) == 'friends' ? 'selected' : '' }}>Friends Only</option>
                                            <option value="private" {{ old('profile_visibility', $user->profile?->profile_visibility) == 'private' ? 'selected' : '' }}>Private</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Show Online Status</div>
                                        <div class="setting-description">Display when you're online to other users</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_online_status" 
                                                   {{ old('show_online_status', $user->profile?->show_online_status) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Allow Challenges</div>
                                        <div class="setting-description">Let other users challenge you to typing races</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="allow_challenges" 
                                                   {{ old('allow_challenges', $user->profile?->allow_challenges) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Show Statistics</div>
                                        <div class="setting-description">Display your typing statistics on your profile</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_statistics" 
                                                   {{ old('show_statistics', $user->profile?->show_statistics ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-bell"></i>
                                Notification Preferences
                            </h3>
                            
                            <div class="notification-settings">
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Email Notifications</div>
                                        <div class="setting-description">Receive notifications via email</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="email_notifications" 
                                                   {{ old('email_notifications', $user->profile?->email_notifications ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Competition Invites</div>
                                        <div class="setting-description">Get notified about new competitions</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="competition_notifications" 
                                                   {{ old('competition_notifications', $user->profile?->competition_notifications ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Achievement Alerts</div>
                                        <div class="setting-description">Get notified when you earn new badges</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="achievement_notifications" 
                                                   {{ old('achievement_notifications', $user->profile?->achievement_notifications ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Weekly Reports</div>
                                        <div class="setting-description">Receive weekly progress summaries</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="weekly_reports" 
                                                   {{ old('weekly_reports', $user->profile?->weekly_reports) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Display Preferences -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-palette"></i>
                                Display Preferences
                            </h3>
                            
                            <div class="display-settings">
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Theme</div>
                                        <div class="setting-description">Choose your preferred color scheme</div>
                                    </div>
                                    <div class="setting-control">
                                        <select class="form-control" name="theme">
                                            <option value="light" {{ old('theme', $user->profile?->theme) == 'light' ? 'selected' : '' }}>Light</option>
                                            <option value="dark" {{ old('theme', $user->profile?->theme) == 'dark' ? 'selected' : '' }}>Dark</option>
                                            <option value="auto" {{ old('theme', $user->profile?->theme) == 'auto' ? 'selected' : '' }}>Auto (System)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Font Size</div>
                                        <div class="setting-description">Adjust text size for typing tests</div>
                                    </div>
                                    <div class="setting-control">
                                        <select class="form-control" name="font_size">
                                            <option value="small" {{ old('font_size', $user->profile?->font_size) == 'small' ? 'selected' : '' }}>Small</option>
                                            <option value="medium" {{ old('font_size', $user->profile?->font_size) == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="large" {{ old('font_size', $user->profile?->font_size) == 'large' ? 'selected' : '' }}>Large</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Show Virtual Keyboard</div>
                                        <div class="setting-description">Display on-screen keyboard during tests</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="show_virtual_keyboard" 
                                                   {{ old('show_virtual_keyboard', $user->profile?->show_virtual_keyboard ?? true) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="setting-item">
                                    <div class="setting-info">
                                        <div class="setting-name">Sound Effects</div>
                                        <div class="setting-description">Play sounds during typing</div>
                                    </div>
                                    <div class="setting-control">
                                        <div class="form-switch">
                                            <input class="form-check-input" type="checkbox" name="sound_effects" 
                                                   {{ old('sound_effects', $user->profile?->sound_effects) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Actions -->
                        <div class="edit-section">
                            <h3 class="section-title">
                                <i class="fas fa-cog"></i>
                                Account Actions
                            </h3>
                            
                            <div class="account-actions">
                                <a href="{{ route('password.change') }}" class="action-btn secondary">
                                    <i class="fas fa-key"></i>
                                    <div class="action-content">
                                        <div class="action-name">Change Password</div>
                                        <div class="action-description">Update your account password</div>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                
                                <button type="button" class="action-btn secondary" onclick="exportData()">
                                    <i class="fas fa-download"></i>
                                    <div class="action-content">
                                        <div class="action-name">Export Data</div>
                                        <div class="action-description">Download your typing statistics</div>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                
                                <button type="button" class="action-btn danger" onclick="confirmDeleteAccount()">
                                    <i class="fas fa-trash-alt"></i>
                                    <div class="action-content">
                                        <div class="action-name">Delete Account</div>
                                        <div class="action-description">Permanently remove your account</div>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                        <i class="fas fa-undo"></i>
                        Reset Changes
                    </button>
                    <button type="submit" class="btn btn-primary" id="save-btn">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <p><strong>This action cannot be undone.</strong></p>
                    <p>Deleting your account will permanently remove:</p>
                    <ul>
                        <li>Your profile and personal information</li>
                        <li>All typing statistics and progress</li>
                        <li>Achievement badges and records</li>
                        <li>Competition history and results</li>
                    </ul>
                    <p>Are you sure you want to proceed?</p>
                </div>
                
                <div class="confirmation-input">
                    <label for="delete-confirmation">Type "DELETE" to confirm:</label>
                    <input type="text" class="form-control" id="delete-confirmation" placeholder="Type DELETE here">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn" disabled onclick="deleteAccount()">
                    <i class="fas fa-trash-alt"></i>
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.profile-edit-container {
    background: var(--bg-primary);
    min-height: calc(100vh - 120px);
    padding: 2rem 0;
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.back-navigation .btn {
    color: var(--text-secondary);
    padding: 0.5rem 0;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.back-navigation .btn:hover {
    color: var(--accent-primary);
}

.page-title {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    color: var(--accent-primary);
}

.page-subtitle {
    color: var(--text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Edit Sections */
.edit-section {
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.section-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
}

.section-title i {
    color: var(--accent-primary);
}

/* Avatar Edit */
.avatar-edit {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.avatar-preview {
    text-align: center;
}

.current-avatar {
    margin-bottom: 1rem;
}

.current-avatar img,
.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--border-light);
}

.avatar-placeholder {
    background: var(--accent-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
}

.avatar-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.avatar-guidelines {
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.avatar-guidelines h4 {
    color: var(--text-primary);
    font-size: 1rem;
    margin-bottom: 1rem;
}

.avatar-guidelines ul {
    margin: 0;
    padding-left: 1.5rem;
    color: var(--text-secondary);
}

.avatar-guidelines li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.form-label i {
    color: var(--accent-primary);
    width: 16px;
}

.form-control {
    background: var(--bg-primary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-help {
    font-size: 0.85rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
}

.form-error {
    font-size: 0.85rem;
    color: var(--accent-danger);
    margin-top: 0.5rem;
}

.char-count {
    display: flex;
    justify-content: flex-end;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.goals-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.goal-item {
    text-align: center;
}

.goal-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.5rem;
}

/* Settings Styles */
.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    background: var(--bg-secondary);
    transition: background 0.3s ease;
}

.setting-item:hover {
    background: var(--border-light);
}

.setting-info {
    flex: 1;
}

.setting-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.setting-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.setting-control {
    min-width: 120px;
}

.form-switch {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.form-check-input {
    width: 50px;
    height: 25px;
    background-color: var(--border-light);
    border: none;
    border-radius: 25px;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: var(--accent-primary);
}

.form-check-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Account Actions */
.action-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
    padding: 1rem;
    background: var(--bg-secondary);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
}

.action-btn:hover {
    background: var(--border-light);
    transform: translateX(4px);
}

.action-btn.danger:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: var(--accent-danger);
    color: var(--accent-danger);
}

.action-btn i:first-child {
    width: 20px;
    text-align: center;
}

.action-content {
    flex: 1;
}

.action-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.action-btn i:last-child {
    color: var(--text-muted);
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 2rem;
    background: var(--bg-card);
    border: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg);
    position: sticky;
    bottom: 2rem;
}

.form-actions .btn {
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: var(--border-radius-lg);
}

.modal-header {
    background: var(--bg-card);
    border-bottom: 1px solid var(--border-light);
    border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.delete-warning {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.delete-warning ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.confirmation-input {
    margin-top: 1rem;
}

.confirmation-input label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .avatar-edit {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .form-row,
    .goals-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-edit-container {
        padding: 1rem 0;
    }
    
    .edit-section {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        position: static;
    }
    
    .setting-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .setting-control {
        width: 100%;
    }
    
    .form-switch {
        justify-content: flex-start;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Avatar upload preview
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (avatarPreview.tagName === 'IMG') {
                    avatarPreview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '120px';
                    img.style.height = '120px';
                    img.style.borderRadius = '50%';
                    img.style.objectFit = 'cover';
                    img.style.border = '3px solid var(--border-light)';
                    avatarPreview.parentNode.replaceChild(img, avatarPreview);
                }
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Bio character counter
    const bioTextarea = document.getElementById('bio');
    const bioCount = document.getElementById('bio-count');
    
    bioTextarea.addEventListener('input', function() {
        const count = this.value.length;
        bioCount.textContent = count;
        
        if (count > 500) {
            bioCount.style.color = 'var(--accent-danger)';
        } else if (count > 450) {
            bioCount.style.color = 'var(--accent-warning)';
        } else {
            bioCount.style.color = 'var(--text-secondary)';
        }
    });
    
    // Delete confirmation
    const deleteConfirmation = document.getElementById('delete-confirmation');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    
    deleteConfirmation.addEventListener('input', function() {
        confirmDeleteBtn.disabled = this.value !== 'DELETE';
    });
    
    // Form validation
    const form = document.getElementById('profile-form');
    const saveBtn = document.getElementById('save-btn');
    
    form.addEventListener('submit', function(e) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });
    
    // Detect form changes
    let formChanged = false;
    const formElements = form.querySelectorAll('input, select, textarea');
    
    formElements.forEach(element => {
        element.addEventListener('change', function() {
            formChanged = true;
            updateSaveButton();
        });
    });
    
    function updateSaveButton() {
        if (formChanged) {
            saveBtn.classList.add('btn-warning');
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        } else {
            saveBtn.classList.remove('btn-warning');
            saveBtn.innerHTML = '<i class="fas fa-check"></i> Saved';
        }
    }
    
    // Warn before leaving if form has changes
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
});

// Global functions
function removeAvatar() {
    const avatarPreview = document.getElementById('avatar-preview');
    const username = '{{ $user->username }}';
    const initials = username.substring(0, 2).toUpperCase();
    
    // Create placeholder div
    const placeholder = document.createElement('div');
    placeholder.id = 'avatar-preview';
    placeholder.className = 'avatar-placeholder';
    placeholder.textContent = initials;
    placeholder.style.width = '120px';
    placeholder.style.height = '120px';
    placeholder.style.borderRadius = '50%';
    placeholder.style.background = 'var(--accent-primary)';
    placeholder.style.color = 'white';
    placeholder.style.display = 'flex';
    placeholder.style.alignItems = 'center';
    placeholder.style.justifyContent = 'center';
    placeholder.style.fontSize = '2rem';
    placeholder.style.fontWeight = '700';
    placeholder.style.border = '3px solid var(--border-light)';
    
    avatarPreview.parentNode.replaceChild(placeholder, avatarPreview);
    
    // Clear file input
    document.getElementById('avatar-input').value = '';
    
    // Add hidden input to indicate avatar removal
    if (!document.querySelector('input[name="remove_avatar"]')) {
        const removeInput = document.createElement('input');
        removeInput.type = 'hidden';
        removeInput.name = 'remove_avatar';
        removeInput.value = '1';
        document.getElementById('profile-form').appendChild(removeInput);
    }
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes?')) {
        location.reload();
    }
}

function confirmDeleteAccount() {
    const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
    modal.show();
}

function deleteAccount() {
    // In real app, this would make an AJAX request to delete account
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("profile.delete") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    form.appendChild(csrfToken);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

function exportData() {
    // In real app, this would trigger data export
    alert('Data export feature will be available soon!');
}
</script>
@endsection