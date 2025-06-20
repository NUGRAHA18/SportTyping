class ProfileManager {
    constructor(options = {}) {
        this.config = {
            avatarInput: options.avatarInput || "#avatar-input",
            avatarPreview: options.avatarPreview || "#avatar-preview",
            cropModal: options.cropModal || "#crop-modal",
            maxFileSize: options.maxFileSize || 5 * 1024 * 1024, // 5MB
            allowedTypes: options.allowedTypes || [
                "image/jpeg",
                "image/png",
                "image/gif",
            ],
            cropAspectRatio: options.cropAspectRatio || 1, // Square
            apiEndpoint: options.apiEndpoint || "/api/profile",
        };

        this.cropper = null;
        this.selectedFile = null;

        this.init();
    }

    init() {
        this.setupAvatarUpload();
        this.setupFormValidation();
        this.setupGoalManagement();
        this.setupPrivacySettings();
        this.setupAccountActions();
    }

    setupAvatarUpload() {
        const avatarInput = document.querySelector(this.config.avatarInput);
        const avatarPreview = document.querySelector(this.config.avatarPreview);

        if (avatarInput) {
            avatarInput.addEventListener("change", (e) =>
                this.handleAvatarSelection(e)
            );
        }

        // Drag and drop support
        if (avatarPreview) {
            avatarPreview.addEventListener("dragover", (e) =>
                this.handleDragOver(e)
            );
            avatarPreview.addEventListener("drop", (e) => this.handleDrop(e));
            avatarPreview.addEventListener("click", () => avatarInput?.click());
        }
    }

    handleAvatarSelection(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file
        if (!this.validateFile(file)) return;

        this.selectedFile = file;
        this.showCropModal(file);
    }

    validateFile(file) {
        // Check file type
        if (!this.config.allowedTypes.includes(file.type)) {
            SportTyping.utils.showNotification(
                "Please select a valid image file (JPEG, PNG, or GIF)",
                "error"
            );
            return false;
        }

        // Check file size
        if (file.size > this.config.maxFileSize) {
            const maxSizeMB = this.config.maxFileSize / (1024 * 1024);
            SportTyping.utils.showNotification(
                `File size must be less than ${maxSizeMB}MB`,
                "error"
            );
            return false;
        }

        return true;
    }

    showCropModal(file) {
        const modal = document.querySelector(this.config.cropModal);
        if (!modal) {
            // If no crop modal, just preview the image
            this.previewImage(file);
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const cropImage = modal.querySelector("#crop-image");
            if (cropImage) {
                cropImage.src = e.target.result;
                this.initCropper(cropImage);
            }
        };
        reader.readAsDataURL(file);

        // Show modal
        if (window.bootstrap) {
            const modalInstance = new window.bootstrap.Modal(modal);
            modalInstance.show();
        }
    }

    initCropper(image) {
        // Destroy existing cropper
        if (this.cropper) {
            this.cropper.destroy();
        }

        // Initialize new cropper (using Cropper.js if available)
        if (window.Cropper) {
            this.cropper = new window.Cropper(image, {
                aspectRatio: this.config.cropAspectRatio,
                viewMode: 2,
                dragMode: "move",
                autoCropArea: 0.8,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        }

        // Setup crop buttons
        this.setupCropButtons();
    }

    setupCropButtons() {
        const modal = document.querySelector(this.config.cropModal);
        if (!modal) return;

        const cropBtn = modal.querySelector("#crop-apply");
        const cancelBtn = modal.querySelector("#crop-cancel");

        if (cropBtn) {
            cropBtn.onclick = () => this.applyCrop();
        }

        if (cancelBtn) {
            cancelBtn.onclick = () => this.cancelCrop();
        }
    }

    applyCrop() {
        if (!this.cropper) return;

        const canvas = this.cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingQuality: "high",
        });

        // Convert to blob and preview
        canvas.toBlob(
            (blob) => {
                this.previewCroppedImage(blob);
                this.closeCropModal();
            },
            "image/jpeg",
            0.9
        );
    }

    cancelCrop() {
        this.closeCropModal();
        // Reset file input
        const avatarInput = document.querySelector(this.config.avatarInput);
        if (avatarInput) {
            avatarInput.value = "";
        }
    }

    closeCropModal() {
        const modal = document.querySelector(this.config.cropModal);
        if (modal && window.bootstrap) {
            const modalInstance = window.bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }

        if (this.cropper) {
            this.cropper.destroy();
            this.cropper = null;
        }
    }

    previewCroppedImage(blob) {
        const url = URL.createObjectURL(blob);
        this.updateAvatarPreview(url);

        // Store blob for upload
        this.croppedBlob = blob;
    }

    previewImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            this.updateAvatarPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    updateAvatarPreview(src) {
        const preview = document.querySelector(this.config.avatarPreview);
        if (!preview) return;

        if (preview.tagName === "IMG") {
            preview.src = src;
        } else {
            // If it's a div or other element, set as background or add img
            let img = preview.querySelector("img");
            if (!img) {
                img = document.createElement("img");
                preview.innerHTML = "";
                preview.appendChild(img);
            }
            img.src = src;
            img.style.width = "100%";
            img.style.height = "100%";
            img.style.objectFit = "cover";
            img.style.borderRadius = "50%";
        }

        // Show upload button if exists
        const uploadBtn = document.querySelector("#upload-avatar-btn");
        if (uploadBtn) {
            uploadBtn.style.display = "block";
        }
    }

    async uploadAvatar() {
        const blob = this.croppedBlob || this.selectedFile;
        if (!blob) {
            SportTyping.utils.showNotification(
                "Please select an image first",
                "error"
            );
            return;
        }

        const formData = new FormData();
        formData.append("avatar", blob);

        try {
            const uploadBtn = document.querySelector("#upload-avatar-btn");
            if (uploadBtn) {
                uploadBtn.classList.add("loading");
                uploadBtn.disabled = true;
            }

            const response = await fetch(`${this.config.apiEndpoint}/avatar`, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": SportTyping.config.csrf,
                },
            });

            if (response.ok) {
                const result = await response.json();
                SportTyping.utils.showNotification(
                    "Avatar updated successfully!",
                    "success"
                );

                // Update all avatar instances on page
                this.updateAllAvatars(result.avatar_url);
            } else {
                throw new Error("Upload failed");
            }
        } catch (error) {
            console.error("Avatar upload failed:", error);
            SportTyping.utils.showNotification(
                "Failed to upload avatar",
                "error"
            );
        } finally {
            const uploadBtn = document.querySelector("#upload-avatar-btn");
            if (uploadBtn) {
                uploadBtn.classList.remove("loading");
                uploadBtn.disabled = false;
            }
        }
    }

    updateAllAvatars(avatarUrl) {
        // Update all avatar images on the page
        const avatars = document.querySelectorAll("[data-user-avatar]");
        avatars.forEach((avatar) => {
            if (avatar.tagName === "IMG") {
                avatar.src = avatarUrl;
            } else {
                const img = avatar.querySelector("img");
                if (img) {
                    img.src = avatarUrl;
                }
            }
        });
    }

    handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.add("drag-over");
    }

    handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.remove("drag-over");

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            this.selectedFile = files[0];
            if (this.validateFile(this.selectedFile)) {
                this.showCropModal(this.selectedFile);
            }
        }
    }

    setupFormValidation() {
        const profileForm = document.querySelector("#profile-form");
        if (!profileForm) return;

        // Real-time validation
        const inputs = profileForm.querySelectorAll("input, textarea, select");
        inputs.forEach((input) => {
            input.addEventListener("blur", () => this.validateField(input));
            input.addEventListener("input", () => this.clearFieldError(input));
        });

        // Form submission
        profileForm.addEventListener("submit", (e) => this.handleFormSubmit(e));
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = "";

        // Field-specific validation
        switch (fieldName) {
            case "name":
                if (value.length < 2) {
                    isValid = false;
                    errorMessage = "Name must be at least 2 characters";
                } else if (value.length > 50) {
                    isValid = false;
                    errorMessage = "Name must be less than 50 characters";
                }
                break;

            case "email":
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = "Please enter a valid email address";
                }
                break;

            case "bio":
                if (value.length > 500) {
                    isValid = false;
                    errorMessage = "Bio must be less than 500 characters";
                }
                break;
        }

        this.showFieldError(field, isValid ? "" : errorMessage);
        return isValid;
    }

    showFieldError(field, message) {
        const errorElement = field.parentElement.querySelector(".field-error");

        if (message) {
            field.classList.add("is-invalid");
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = "block";
            }
        } else {
            field.classList.remove("is-invalid");
            if (errorElement) {
                errorElement.style.display = "none";
            }
        }
    }

    clearFieldError(field) {
        field.classList.remove("is-invalid");
        const errorElement = field.parentElement.querySelector(".field-error");
        if (errorElement) {
            errorElement.style.display = "none";
        }
    }

    async handleFormSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        // Validate all fields
        const inputs = form.querySelectorAll("input, textarea, select");
        let isFormValid = true;

        inputs.forEach((input) => {
            if (!this.validateField(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            SportTyping.utils.showNotification(
                "Please fix the errors below",
                "error"
            );
            return;
        }

        try {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add("loading");
                submitBtn.disabled = true;
            }

            const response = await fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": SportTyping.config.csrf,
                },
            });

            if (response.ok) {
                const result = await response.json();
                SportTyping.utils.showNotification(
                    "Profile updated successfully!",
                    "success"
                );

                // Update page content if needed
                if (result.user) {
                    this.updateProfileDisplay(result.user);
                }
            } else {
                const error = await response.json();
                throw new Error(error.message || "Update failed");
            }
        } catch (error) {
            console.error("Profile update failed:", error);
            SportTyping.utils.showNotification(
                error.message || "Failed to update profile",
                "error"
            );
        } finally {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.remove("loading");
                submitBtn.disabled = false;
            }
        }
    }

    updateProfileDisplay(user) {
        // Update displayed name
        const nameElements = document.querySelectorAll("[data-user-name]");
        nameElements.forEach((el) => {
            el.textContent = user.name;
        });

        // Update bio
        const bioElements = document.querySelectorAll("[data-user-bio]");
        bioElements.forEach((el) => {
            el.textContent = user.bio || "No bio available";
        });
    }

    setupGoalManagement() {
        // Goal setting functionality
        const addGoalBtn = document.querySelector("#add-goal-btn");
        if (addGoalBtn) {
            addGoalBtn.addEventListener("click", () => this.showGoalModal());
        }

        // Goal deletion
        document.addEventListener("click", (e) => {
            if (e.target.closest(".delete-goal-btn")) {
                const goalId =
                    e.target.closest(".delete-goal-btn").dataset.goalId;
                this.deleteGoal(goalId);
            }
        });
    }

    showGoalModal(goal = null) {
        const modal = document.querySelector("#goal-modal");
        if (!modal) return;

        // Reset form
        const form = modal.querySelector("#goal-form");
        if (form) {
            form.reset();
        }

        // Pre-fill if editing
        if (goal) {
            // Fill form with goal data
            Object.keys(goal).forEach((key) => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = goal[key];
                }
            });
        }

        if (window.bootstrap) {
            const modalInstance = new window.bootstrap.Modal(modal);
            modalInstance.show();
        }
    }

    async deleteGoal(goalId) {
        if (!confirm("Are you sure you want to delete this goal?")) return;

        try {
            const response = await SportTyping.utils.api(`/goals/${goalId}`, {
                method: "DELETE",
            });

            SportTyping.utils.showNotification(
                "Goal deleted successfully",
                "success"
            );

            // Remove goal element from DOM
            const goalElement = document.querySelector(
                `[data-goal-id="${goalId}"]`
            );
            if (goalElement) {
                goalElement.remove();
            }
        } catch (error) {
            SportTyping.utils.showNotification(
                "Failed to delete goal",
                "error"
            );
        }
    }

    setupPrivacySettings() {
        // Privacy toggles
        const privacyToggles = document.querySelectorAll(".privacy-toggle");
        privacyToggles.forEach((toggle) => {
            toggle.addEventListener("change", (e) =>
                this.updatePrivacySetting(e)
            );
        });
    }

    async updatePrivacySetting(e) {
        const setting = e.target.name;
        const value = e.target.checked;

        try {
            await SportTyping.utils.api("/profile/privacy", {
                method: "POST",
                body: JSON.stringify({
                    setting: setting,
                    value: value,
                }),
            });

            SportTyping.utils.showNotification(
                "Privacy setting updated",
                "success"
            );
        } catch (error) {
            // Revert toggle
            e.target.checked = !value;
            SportTyping.utils.showNotification(
                "Failed to update privacy setting",
                "error"
            );
        }
    }

    setupAccountActions() {
        // Account deletion
        const deleteAccountBtn = document.querySelector("#delete-account-btn");
        if (deleteAccountBtn) {
            deleteAccountBtn.addEventListener("click", () =>
                this.showDeleteAccountModal()
            );
        }

        // Export data
        const exportDataBtn = document.querySelector("#export-data-btn");
        if (exportDataBtn) {
            exportDataBtn.addEventListener("click", () =>
                this.exportUserData()
            );
        }
    }

    showDeleteAccountModal() {
        const modal = document.querySelector("#delete-account-modal");
        if (!modal) return;

        if (window.bootstrap) {
            const modalInstance = new window.bootstrap.Modal(modal);
            modalInstance.show();
        }
    }

    async exportUserData() {
        try {
            const response = await SportTyping.utils.api("/profile/export", {
                method: "POST",
            });

            const blob = new Blob([JSON.stringify(response, null, 2)], {
                type: "application/json",
            });

            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `sporttyping-data-${
                new Date().toISOString().split("T")[0]
            }.json`;
            a.click();

            URL.revokeObjectURL(url);

            SportTyping.utils.showNotification(
                "Data exported successfully",
                "success"
            );
        } catch (error) {
            SportTyping.utils.showNotification(
                "Failed to export data",
                "error"
            );
        }
    }
}

// Auto-initialize profile manager
SportTyping.autoInit.register("[data-profile-manager]", (element) => {
    const options = element.dataset.profileManager
        ? JSON.parse(element.dataset.profileManager)
        : {};
    new ProfileManager(options);
});

// Export for global access
window.ProfileManager = ProfileManager;
