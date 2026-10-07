@extends('player.portal.layout')

@section('title', 'Support & Help - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-life-ring me-3 text-primary"></i>Support & Help
                    </h1>
                    <p class="section-subtitle">Get help with your academy experience and account</p>
                </div>

                <div class="header-actions">
                    <button class="btn btn-success">
                        <i class="fas fa-comments me-1"></i>Live Chat
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Help Options -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="100">
            <div class="section-header">
                <h3 class="section-title">Quick Help</h3>
            </div>

            <div class="quick-help-grid">
                <div class="help-card" data-aos="zoom-in">
                    <div class="help-icon">
                        <i class="fas fa-question-circle text-info"></i>
                    </div>
                    <div class="help-content">
                        <h4>FAQ</h4>
                        <p>Find answers to common questions about training, schedules, and academy policies</p>
                        <a href="#" class="help-link">Browse FAQ →</a>
                    </div>
                </div>

                <div class="help-card" data-aos="zoom-in" data-aos-delay="50">
                    <div class="help-icon">
                        <i class="fas fa-user-md text-success"></i>
                    </div>
                    <div class="help-content">
                        <h4>Medical Support</h4>
                        <p>Get help with injury reports, treatment plans, and medical clearance</p>
                        <a href="#" class="help-link">Medical Portal →</a>
                    </div>
                </div>

                <div class="help-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="help-icon">
                        <i class="fas fa-graduation-cap text-primary"></i>
                    </div>
                    <div class="help-content">
                        <h4>Academic Help</h4>
                        <p>Support with education requirements and academic performance tracking</p>
                        <a href="#" class="help-link">Academic Hub →</a>
                    </div>
                </div>

                <div class="help-card" data-aos="zoom-in" data-aos-delay="150">
                    <div class="help-icon">
                        <i class="fas fa-tools text-warning"></i>
                    </div>
                    <div class="help-content">
                        <h4>Technical Support</h4>
                        <p>Help with account issues, password resets, and system troubleshooting</p>
                        <a href="#" class="help-link">Get Support →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Submit Support Ticket -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="portal-section">
                    <div class="section-header">
                        <h3 class="section-title">Submit Support Request</h3>
                        <p class="section-subtitle">Our team will respond within 24 hours</p>
                    </div>

                    <form class="support-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="supportCategory" class="form-label">Category</label>
                                <select class="form-select" id="supportCategory" required>
                                    <option value="">Choose category...</option>
                                    <option value="training">Training & Performance</option>
                                    <option value="medical">Medical & Injuries</option>
                                    <option value="equipment">Equipment & Facilities</option>
                                    <option value="schedule">Schedule & Attendance</option>
                                    <option value="academic">Academic Support</option>
                                    <option value="technical">Technical Issues</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="urgencyLevel" class="form-label">Urgency</label>
                                <select class="form-select" id="urgencyLevel" required>
                                    <option value="low">Low - General help needed</option>
                                    <option value="medium" selected>Medium - Need help soon</option>
                                    <option value="high">High - Urgent assistance required</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="supportSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="supportSubject" placeholder="Brief description of your issue" required>
                        </div>

                        <div class="mb-3">
                            <label for="supportMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="supportMessage" rows="6" placeholder="Please provide detailed information about your issue or question..." required></textarea>
                            <div class="form-text">
                                Include any relevant details, dates, times, or specific concerns to help us assist you better.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attach Files (Optional)</label>
                            <div class="file-upload-area">
                                <input type="file" id="fileUpload" multiple accept="image/*,.pdf,.doc,.docx">
                                <label for="fileUpload" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt me-2"></i>
                                    Click to upload or drag files here
                                </label>
                                <div class="upload-progress" style="display: none;">
                                    <div class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="urgentResponse">
                            <label class="form-check-label" for="urgentResponse">
                                This requires an urgent response (within 4 hours)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Submit Support Request
                        </button>
                    </form>
                </div>

                <!-- Recent Support History -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-header">
                        <h3 class="section-title">Recent Support History</h3>
                    </div>

                    <div class="support-history">
                        <div class="support-ticket">
                            <div class="ticket-header">
                                <h5>Missing training equipment request</h5>
                                <span class="badge bg-success">Resolved</span>
                            </div>
                            <div class="ticket-meta">
                                <span>Opened: Nov 10, 2024</span>
                                <span>Response: Same day</span>
                            </div>
                            <p class="ticket-description">Requested replacement for missing training cone set that was damaged during practice.</p>
                        </div>

                        <div class="support-ticket">
                            <div class="ticket-header">
                                <h5>Schedule conflict question</h5>
                                <span class="badge bg-success">Resolved</span>
                            </div>
                            <div class="ticket-meta">
                                <span>Opened: Nov 8, 2024</span>
                                <span>Response: 2 hours</span>
                            </div>
                            <p class="ticket-description">Inquired about upcoming tournament schedule conflict with school exams.</p>
                        </div>

                        <div class="support-ticket">
                            <div class="ticket-header">
                                <h5>Sports injury consultation</h5>
                                <span class="badge bg-warning">In Progress</span>
                            </div>
                            <div class="ticket-meta">
                                <span>Opened: Nov 5, 2024</span>
                                <span>Response: 3 hours</span>
                            </div>
                            <p class="ticket-description">Ankle sprain during training - seeking medical advice and recovery plan.</p>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-history me-1"></i>View All Tickets
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Information & Resources -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <!-- Contact Info -->
                <div class="portal-section">
                    <div class="section-header">
                        <h4 class="section-title">Contact Information</h4>
                    </div>

                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-phone text-success"></i>
                            </div>
                            <div class="method-content">
                                <h6>Phone Support</h6>
                                <p>+254 XXX XXX XXX</p>
                                <small>Mon-Fri: 8 AM - 6 PM</small>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div class="method-content">
                                <h6>Email Support</h6>
                                <p>support@vipersacademy.go.ke</p>
                                <small>Response within 24 hours</small>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-comments text-info"></i>
                            </div>
                            <div class="method-content">
                                <h6>Live Chat</h6>
                                <p>Available Mon-Fri 9 AM - 5 PM</p>
                                <small>Average wait: 2 minutes</small>
                            </div>
                        </div>

                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                            </div>
                            <div class="method-content">
                                <h6>Visit Academy</h6>
                                <p>Moi International Sports Centre</p>
                                <small>By appointment only</small>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block mt-3">
                        <i class="fas fa-calendar-plus me-1"></i>Schedule Callback
                    </button>
                </div>

                <!-- Help Resources -->
                <div class="portal-section">
                    <div class="section-header">
                        <h4 class="section-title">Help Resources</h4>
                    </div>

                    <div class="resource-list">
                        <a href="#" class="resource-item">
                            <i class="fas fa-book me-2"></i>
                            <div>
                                <div class="resource-title">Player Handbook</div>
                                <div class="resource-meta">PDF • 45 pages</div>
                            </div>
                        </a>

                        <a href="#" class="resource-item">
                            <i class="fas fa-video me-2"></i>
                            <div>
                                <div class="resource-title">Getting Started Guide</div>
                                <div class="resource-meta">Video • 5:30</div>
                            </div>
                        </a>

                        <a href="#" class="resource-item">
                            <i class="fas fa-shield-alt me-2"></i>
                            <div>
                                <div class="resource-title">Safety Guidelines</div>
                                <div class="resource-meta">PDF • 12 pages</div>
                            </div>
                        </a>

                        <a href="#" class="resource-item">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <div>
                                <div class="resource-title">Academic Policies</div>
                                <div class="resource-meta">Document • 8 pages</div>
                            </div>
                        </a>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download me-1"></i>Download All Resources
                        </button>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="portal-section border-danger">
                    <div class="emergency-notice">
                        <div class="emergency-icon">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                        <div class="emergency-content">
                            <h5 class="text-danger">Emergency Contact</h5>
                            <p>For immediate medical emergencies or urgent situations:</p>
                            <div class="emergency-phone">
                                <strong>Emergency: +254 XXX XXX XXX</strong>
                            </div>
                            <small class="text-muted">24/7 availability</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Quick Help Grid */
.quick-help-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.help-card {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: white;
    border: var(--border-light);
    border-radius: 16px;
    transition: var(--transition-normal);
    text-decoration: none;
    color: inherit;
}

.help-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
    color: inherit;
}

.help-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--bg-tertiary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.help-content h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.help-content p {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 12px;
    line-height: 1.4;
}

.help-link {
    font-weight: 600;
    color: var(--primary-red);
    text-decoration: none;
    transition: var(--transition-normal);
}

.help-link:hover {
    color: var(--primary-red-dark);
}

/* Support Form */
.support-form {
    max-width: none;
}

.file-upload-area {
    border: 2px dashed var(--border);
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    transition: var(--transition-normal);
    position: relative;
}

.file-upload-area:hover {
    border-color: var(--primary-red);
    background: rgba(234, 28, 77, 0.02);
}

.file-upload-area input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-upload-label {
    cursor: pointer;
    color: var(--text-secondary);
    font-weight: 500;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-progress {
    margin-top: 16px;
}

.upload-progress .progress-bar {
    height: 6px;
    background: var(--primary-red);
}

/* Support History */
.support-history {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.support-ticket {
    padding: 16px;
    background: white;
    border: var(--border-light);
    border-radius: 8px;
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 8px;
}

.ticket-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    flex: 1;
}

.ticket-meta {
    display: flex;
    gap: 12px;
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.ticket-description {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.4;
}

/* Contact Methods */
.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 12px;
}

.method-icon {
    width: 40px;
    height: 40px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.method-content h6 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
}

.method-content p {
    margin: 0 0 4px 0;
    font-size: 13px;
    color: var(--accent-blue);
    font-weight: 500;
}

.method-content small {
    color: var(--text-muted);
    font-size: 11px;
}

/* Resource List */
.resource-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.resource-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-primary);
    transition: var(--transition-normal);
}

.resource-item:hover {
    background: var(--primary-red-light);
    color: var(--text-primary);
}

.resource-title {
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 2px;
}

.resource-meta {
    font-size: 12px;
    color: var(--text-muted);
}

/* Emergency Notice */
.emergency-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.02));
    border-radius: 12px;
}

.emergency-icon {
    width: 40px;
    height: 40px;
    background: var(--danger);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.emergency-phone {
    font-size: 16px;
    margin: 8px 0 4px 0;
}

/* Responsive */
@media (max-width: 768px) {
    .quick-help-grid {
        grid-template-columns: 1fr;
    }

    .help-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .support-form .row {
        --bs-gutter-x: 0;
    }

    .file-upload-area {
        padding: 32px 16px;
    }

    .contact-method {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }

    .emergency-notice {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload functionality
    const fileInput = document.getElementById('fileUpload');
    const uploadArea = document.querySelector('.file-upload-area');
    const uploadProgress = document.querySelector('.upload-progress');
    const progressBar = document.querySelector('.upload-progress .progress-bar');

    fileInput.addEventListener('change', function() {
        const files = this.files;
        if (files.length > 0) {
            // Show upload progress (simulated)
            uploadProgress.style.display = 'block';
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                progressBar.style.width = progress + '%';
                if (progress >= 100) {
                    clearInterval(interval);
                }
            }, 100);
        }
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--primary-red)';
        this.style.backgroundColor = 'rgba(234, 28, 77, 0.05)';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--border)';
        this.style.backgroundColor = '';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--border)';
        this.style.backgroundColor = '';

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Form submission
    const supportForm = document.querySelector('.support-form');
    supportForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
        submitBtn.disabled = true;

        // Simulate submission
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success mt-3';
            successAlert.innerHTML = '<i class="fas fa-check-circle me-2"></i>Support request submitted successfully! We\'ll respond within 24 hours.';
            this.appendChild(successAlert);

            // Clear form
            this.reset();

            setTimeout(() => {
                successAlert.remove();
            }, 5000);
        }, 2000);
    });
});
</script>
@endpush
@endsection
