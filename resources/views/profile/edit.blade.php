@extends('layouts.app')

@section('content')
<div class="profile-edit-container">
    <div class="container">
        <!-- Header -->
        <div class="edit-header">
            <div class="header-content">
                <div class="breadcrumb">
                    <a href="{{ route('profile.show') }}">
                        <i class="fas fa-arrow-left"></i>
                        Back to Profile
                    </a>
                </div>
                <h1>Edit Profile</h1>
                <p>Update your profile information and preferences</p>
            </div>
        </div>

        <div class="edit-content">
            <!-- Profile Form -->
            <div class="form-section">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PATCH')

                    <!-- Avatar Section -->
                    <div class="form-card">
                        <div class="card-header">
                            <h2><i class="fas fa-user-circle"></i> Profile Picture</h2>
                            <p>Upload a new avatar or change your profile picture</p>
                        </div>
                        
                        <div class="avatar-section">
                            <div class="current-avatar">
                                @if(Auth::user()->profile->avatar)
                                    <img src="{{ Storage::url(Auth::user()->profile->avatar) }}" alt="Current Avatar" id="currentAvatar">
                                @else
                                    <div class="avatar-placeholder" id="currentAvatar">
                                        {{ substr(Auth::user()->username, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="avatar-controls">
                                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('avatarInput').click()">
                                    <i class="fas fa-camera"></i>
                                    Change Avatar
                                </button>
                                @if(Auth::user()->profile->avatar)
                                    <button type="button" class="btn btn-outline-secondary" onclick="removeAvatar()">
                                        <i class="fas fa-trash"></i>
                                        Remove
                                    </button>
                                @endif
                                <div class="avatar-info">
                                    <small>Supported formats: JPG, PNG, GIF (max 2MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="form-card">
                        <div class="card-header">
                            <h2><i class="fas fa-info-circle"></i> Basic Information</h2>
                            <p>Your public profile information</p>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Username
                                </label>
                                <input type="text" 
                                       id="username" 
                                       name="username" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       value="{{ old('username', Auth::user()->username) }}" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-hint">This is how other users will see you</small>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', Auth::user()->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-hint">Used for notifications and account recovery</small>
                            </div>

                            <div class="form-group full-width">
                                <label for="bio" class="form-label">
                                    <i class="fas fa-quote-left"></i>
                                    Bio (Optional)
                                </label>
                                <textarea id="bio" 
                                          name="bio" 
                                          class="form-control @error('bio') is-invalid @enderror" 
                                          rows="4" 
                                          maxlength="500" 
                                          placeholder="Tell others about yourself...">{{ old('bio', Auth::user()->profile->bio ?? '') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="char-counter">
                                    <span id="bioCounter">{{ strlen(Auth::user()->profile->bio ?? '') }}</span>/500 characters
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Location (Optional)
                                </label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       value="{{ old('location', Auth::user()->profile->location ?? '') }}" 
                                       placeholder="e.g. New York, USA">
                                @error('location')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="website" class="form-label">
                                    <i class="fas fa-globe"></i>
                                    Website (Optional)
                                </label>
                                <input type="url" 
                                       id="website" 
                                       name="website" 
                                       class="form-control @error('website') is-invalid @enderror" 
                                       value="{{ old('website', Auth::user()->profile->website ?? '') }}" 
                                       placeholder="https://yourwebsite.com">
                                @error('website')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Typing Preferences -->
                    <div class="form-card">
                        <div class="card-header">
                            <h2><i class="fas fa-keyboard"></i> Typing Preferences</h2>
                            <p>Customize your typing experience</p>
                        </div>
                        
                        <div class="preferences-grid">
                            <div class="preference-group">
                                <h3>Preferred Device</h3>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="preferred_device" value="pc" 
                                               {{ (Auth::user()->profile->preferred_device ?? 'pc') == 'pc' ? 'checked' : '' }}>
                                        <div class="radio-content">
                                            <i class="fas fa-desktop"></i>
                                            <span>Desktop/Laptop</span>
                                        </div>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="preferred_device" value="mobile" 
                                               {{ (Auth::user()->profile->preferred_device ?? 'pc') == 'mobile' ? 'checked' : '' }}>
                                        <div class="radio-content">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>Mobile</span>
                                        </div>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="preferred_device" value="both" 
                                               {{ (Auth::user()->profile->preferred_device ?? 'pc') == 'both' ? 'checked' : '' }}>
                                        <div class="radio-content">
                                            <i class="fas fa-laptop"></i>
                                            <span>Both</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="preference-group">
                                <h3>Preferred Categories</h3>
                                <div class="checkbox-group">
                                    @php
                                        $categories = ['programming', 'literature', 'science', 'business', 'technology', 'random'];
                                        $selectedCategories = json_decode(Auth::user()->profile->preferred_categories ?? '[]', true);
                                    @endphp
                                    @foreach($categories as $category)
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="preferred_categories[]" value="{{ $category }}" 
                                               {{ in_array($category, $selectedCategories) ? 'checked' : '' }}>
                                        <div class="checkbox-content">
                                            <i class="fas fa-{{ $category == 'programming' ? 'code' : ($category == 'literature' ? 'book' : ($category == 'science' ? 'flask' : ($category == 'business' ? 'briefcase' : ($category == 'technology' ? 'microchip' : 'random')))) }}"></i>
                                            <span>{{ ucfirst($category) }}</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy Settings -->
                    <div class="form-card">
                        <div class="card-header">
                            <h2><i class="fas fa-shield-alt"></i> Privacy & Visibility</h2>
                            <p>Control who can see your information</p>
                        </div>
                        
                        <div class="privacy-settings">
                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Public Profile</h4>
                                    <p>Allow others to view your profile and statistics</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="public_profile" value="1" 
                                           {{ (Auth::user()->profile->public_profile ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Show in Leaderboards</h4>
                                    <p>Display your ranking in public leaderboards</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="show_in_leaderboards" value="1" 
                                           {{ (Auth::user()->profile->show_in_leaderboards ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Competition Notifications</h4>
                                    <p>Receive notifications about new competitions</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="competition_notifications" value="1" 
                                           {{ (Auth::user()->profile->competition_notifications ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="setting-item">
                                <div class="setting-info">
                                    <h4>Email Notifications</h4>
                                    <p>Receive email updates about your progress and achievements</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="email_notifications" value="1" 
                                           {{ (Auth::user()->profile->email_notifications ?? true) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <!-- Account Actions -->
            <div class="account-actions">
                <div class="action-card danger">
                    <div class="action-header">
                        <h3><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
                        <p>Irreversible and destructive actions</p>
                    </div>
                    
                    <div class="action-list">
                        <div class="action-item">
                            <div class="action-info">
                                <h4>Change Password</h4>
                                <p>Update your account password for security</p>
                            </div>
                            <button class="btn btn-outline-warning" onclick="showPasswordModal()">
                                <i class="fas fa-key"></i>
                                Change Password
                            </button>
                        </div>

                        <div class="action-item">
                            <div class="action-info">
                                <h4>Reset Statistics</h4>
                                <p>Clear all your typing statistics and start fresh</p>
                            </div>
                            <button class="btn btn-outline-warning" onclick="confirmResetStats()">
                                <i class="fas fa-chart-line"></i>
                                Reset Stats
                            </button>
                        </div>

                        <div class="action-item">
                            <div class="action-info">
                                <h4>Delete Account</h4>
                                <p>Permanently delete your account and all associated data</p>
                            </div>
                            <button class="btn btn-outline-danger" onclick="confirmDeleteAccount()">
                                <i class="fas fa-trash"></i>
                                Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal -->
<div class="modal" id="passwordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-key"></i> Change Password</h3>
            <button class="close-btn" onclick="closePasswordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="passwordForm" action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-body">
                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="password-input">
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <div class="password-input">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" onclick="closePasswordModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.profile-edit-container {
    padding: 2rem 0;
    min-height: calc(100vh - 80px);
}

/* Header */
.edit-header {
    margin-bottom: 3rem;
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

.edit-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.breadcrumb a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: var(--accent-pink);
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.header-content p {
    color: var(--text-secondary);
    font-size: 1.1rem;
}

/* Edit Content */
.edit-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

/* Form Cards */
.form-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    margin-bottom: 2rem;
}

.card-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.card-header h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.card-header p {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

/* Avatar Section */
.avatar-section {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.current-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.current-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gradient-button);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: white;
}

.avatar-controls {
    flex: 1;
}

.avatar-controls .btn {
    margin-right: 1rem;
    margin-bottom: 1rem;
}

.avatar-info {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

/* Form Elements */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
    font-weight: 500;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    color: var(--text-primary);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-pink);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-control.is-invalid {
    border-color: var(--error);
}

.invalid-feedback {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--error);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.form-hint {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.char-counter {
    text-align: right;
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.password-toggle:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

/* Preferences */
.preferences-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.preference-group h3 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 1rem;
}

.radio-group, .checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.radio-option, .checkbox-option {
    cursor: pointer;
}

.radio-option input, .checkbox-option input {
    display: none;
}

.radio-content, .checkbox-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.03);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    transition: all 0.3s ease;
}

.radio-option:hover .radio-content,
.checkbox-option:hover .checkbox-content {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--accent-pink);
}

.radio-option input:checked + .radio-content,
.checkbox-option input:checked + .checkbox-content {
    background: rgba(255, 107, 157, 0.1);
    border-color: var(--accent-pink);
}

.radio-content i, .checkbox-content i {
    color: var(--accent-pink);
    font-size: 1.2rem;
}

/* Privacy Settings */
.privacy-settings {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
}

.setting-info h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.setting-info p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 26px;
    cursor: pointer;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.2);
    transition: 0.3s;
    border-radius: 26px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 3px;
    background: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background: var(--accent-pink);
}

input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-large {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

/* Account Actions */
.account-actions {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.action-card {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
}

.action-card.danger {
    border-color: rgba(239, 68, 68, 0.3);
}

.action-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.action-header h3 {
    color: var(--error);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.action-header p {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.action-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.action-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.action-info h4 {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.action-info p {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(10px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 2rem;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: var(--gradient-card);
    backdrop-filter: blur(var(--blur-amount));
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius-lg);
    width: 100%;
    max-width: 500px;
    position: relative;
}

.modal-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-accent);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 2rem 2rem 0;
}

.modal-header h3 {
    color: var(--text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.close-btn {
    background: none;
    border: none;
    color: var(--text-secondary);
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    color: var(--accent-pink);
    background: rgba(255, 107, 157, 0.1);
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    padding: 0 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 1024px) {
    .edit-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .account-actions {
        position: static;
    }
    
    .preferences-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .action-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
}
</style>

<script>
// Avatar preview
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const avatar = document.getElementById('currentAvatar');
            if (avatar.tagName === 'IMG') {
                avatar.src = e.target.result;
            } else {
                avatar.outerHTML = <img src="${e.target.result}" alt="New Avatar" id="currentAvatar">;
            }
        };
        reader.readAsDataURL(file);
    }
});

// Bio character counter
document.getElementById('bio').addEventListener('input', function() {
    const counter = document.getElementById('bioCounter');
    counter.textContent = this.value.length;
    
    if (this.value.length > 450) {
        counter.style.color = 'var(--warning)';
    } else if (this.value.length > 480) {
        counter.style.color = 'var(--error)';
    } else {
        counter.style.color = 'var(--text-secondary)';
    }
});

// Password modal functions
function showPasswordModal() {
    document.getElementById('passwordModal').classList.add('show');
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('show');
}

function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const toggle = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        toggle.classList.remove('fa-eye');
        toggle.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        toggle.classList.remove('fa-eye-slash');
        toggle.classList.add('fa-eye');
    }
}

// Account actions
function removeAvatar() {
    if (confirm('Are you sure you want to remove your profile picture?')) {
        // Add hidden input to indicate avatar removal
        const form = document.querySelector('.profile-form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_avatar';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
        
        // Update preview
        const avatar = document.getElementById('currentAvatar');
        const username = '{{ Auth::user()->username }}';
        avatar.outerHTML = <div class="avatar-placeholder" id="currentAvatar">${username.substr(0, 2)}</div>;
    }
}

function confirmResetStats() {
    if (confirm('Are you sure you want to reset all your typing statistics? This action cannot be undone.')) {
        // Submit form to reset stats
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("profile.reset-stats") }}';
        
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
}

function confirmDeleteAccount() {
    const confirmation = prompt('This will permanently delete your account and all data. Type "DELETE" to confirm:');
    if (confirmation === 'DELETE') {
        // Submit form to delete account
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
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});

// ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => modal.classList.remove('show'));
    }
});
</script>
@endsection