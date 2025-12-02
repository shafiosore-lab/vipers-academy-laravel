@extends('player.portal.layout')

@section('title', 'Communication - Player Portal')

@section('portal-content')
<div class="row animate-slide-in">
    <!-- Main Content Area -->
    <div class="col-12">
        <!-- Page Header -->
        <div class="portal-section" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h1 class="section-title">
                        <i class="fas fa-comments me-3 text-primary"></i>Communication
                    </h1>
                    <p class="section-subtitle">Stay connected with your coaches, teammates, and academy staff</p>
                </div>

                <div class="header-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>New Message
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Messages List -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="portal-section messages-sidebar">
                    <div class="messages-header">
                        <h4 class="messages-title">Messages</h4>
                        <div class="message-count">3 unread</div>
                    </div>

                    <div class="messages-list">
                        <div class="message-item unread active" data-aos="fade-right">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/40x40?text=CW" alt="Coach Wilson">
                                <div class="message-status online"></div>
                            </div>
                            <div class="message-content">
                                <div class="message-sender">Coach Wilson</div>
                                <div class="message-preview">Great performance in training today! Let's discuss...</div>
                                <div class="message-time">2m ago</div>
                            </div>
                            <div class="message-indicator">
                                <i class="fas fa-circle"></i>
                            </div>
                        </div>

                        <div class="message-item unread" data-aos="fade-right" data-aos-delay="50">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/40x40?text=AM" alt="Academy Manager">
                                <div class="message-status offline"></div>
                            </div>
                            <div class="message-content">
                                <div class="message-sender">Academy Manager</div>
                                <div class="message-preview">Your match schedule has been updated</div>
                                <div class="message-time">1h ago</div>
                            </div>
                            <div class="message-indicator">
                                <i class="fas fa-circle"></i>
                            </div>
                        </div>

                        <div class="message-item read" data-aos="fade-right" data-aos-delay="100">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/40x40?text=PT" alt="Physical Trainer">
                                <div class="message-status away"></div>
                            </div>
                            <div class="message-content">
                                <div class="message-sender">Physical Trainer</div>
                                <div class="message-preview">Recovery plan for next week</div>
                                <div class="message-time">3h ago</div>
                            </div>
                        </div>

                        <div class="message-item read" data-aos="fade-right" data-aos-delay="150">
                            <div class="message-avatar">
                                <span class="avatar-placeholder">TC</span>
                                <div class="message-status online"></div>
                            </div>
                            <div class="message-content">
                                <div class="message-sender">Team Captain</div>
                                <div class="message-preview">Strategy meeting tomorrow</div>
                                <div class="message-time">1d ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Announcements -->
                <div class="portal-section" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-header">
                        <h4 class="section-title">Latest Announcements</h4>
                    </div>

                    <div class="announcements-list">
                        <div class="announcement-item" data-aos="zoom-in">
                            <div class="announcement-header">
                                <div class="announcement-badge">
                                    <i class="fas fa-trophy text-warning"></i>
                                </div>
                                <div class="announcement-date">Today</div>
                            </div>
                            <div class="announcement-content">
                                <h6 class="announcement-title">Tournament Registration Now Open</h6>
                                <p class="announcement-text">Register for the upcoming youth championship</p>
                            </div>
                        </div>

                        <div class="announcement-item" data-aos="zoom-in" data-aos-delay="50">
                            <div class="announcement-header">
                                <div class="announcement-badge">
                                    <i class="fas fa-tools text-primary"></i>
                                </div>
                                <div class="announcement-date">2d ago</div>
                            </div>
                            <div class="announcement-content">
                                <h6 class="announcement-title">Equipment Maintenance</h6>
                                <p class="announcement-text">Gym will be closed for maintenance this Saturday</p>
                            </div>
                        </div>

                        <div class="announcement-item" data-aos="zoom-in" data-aos-delay="100">
                            <div class="announcement-header">
                                <div class="announcement-badge">
                                    <i class="fas fa-calendar-check text-success"></i>
                                </div>
                                <div class="announcement-date">5d ago</div>
                            </div>
                            <div class="announcement-content">
                                <h6 class="announcement-title">Team Meeting</h6>
                                <p class="announcement-text">Weekly team meeting tomorrow at 5 PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-sm btn-outline-primary">View All Announcements</button>
                    </div>
                </div>
            </div>

            <!-- Message View -->
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="portal-section message-thread">
                    <div class="message-thread-header">
                        <div class="thread-participant">
                            <img src="https://via.placeholder.com/40x40?text=CW" alt="Coach Wilson" class="participant-avatar">
                            <div class="participant-info">
                                <h5 class="participant-name">Coach Wilson</h5>
                                <span class="participant-role">Head Coach & Technical Director</span>
                            </div>
                        </div>
                        <div class="thread-actions">
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-phone me-1"></i>Call
                            </button>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-video me-1"></i>Video
                            </button>
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>

                    <div class="message-thread-content">
                        <!-- Messages -->
                        <div class="message received">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/32x32?text=CW" alt="Coach Wilson">
                            </div>
                            <div class="message-bubble">
                                <div class="message-text">
                                    Hey! Great performance in today's training session. Your passing accuracy has really improved.
                                </div>
                                <div class="message-time">2:34 PM</div>
                            </div>
                        </div>

                        <div class="message received">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/32x32?text=CW" alt="Coach Wilson">
                            </div>
                            <div class="message-bubble">
                                <div class="message-text">
                                    Let's work on maintaining your focus during the final 15 minutes of training. I noticed you seemed a bit distracted.
                                </div>
                                <div class="message-time">2:35 PM</div>
                            </div>
                        </div>

                        <div class="message sent">
                            <div class="message-bubble sent">
                                <div class="message-text">
                                    Thanks Coach! I appreciate the feedback. I'll work harder on my focus during training sessions.
                                </div>
                                <div class="message-time">2:37 PM</div>
                            </div>
                        </div>

                        <div class="message received">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/32x32?text=CW" alt="Coach Wilson">
                            </div>
                            <div class="message-bubble">
                                <div class="message-text">
                                    Excellent attitude! Also, I wanted to discuss your role in Saturday's match. Do you have time for a quick chat before tomorrow's session?
                                </div>
                                <div class="message-time">2:38 PM</div>
                            </div>
                        </div>

                        <div class="message sent">
                            <div class="message-bubble sent">
                                <div class="message-text">
                                    Yes, definitely! I'm available after the current session ends.
                                </div>
                                <div class="message-time">2:39 PM</div>
                            </div>
                        </div>

                        <div class="message received">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/32x32?text=CW" alt="Coach Wilson">
                            </div>
                            <div class="message-bubble">
                                <div class="message-text">
                                    Perfect! Let's meet at the side field. Bring your water bottle and I'll explain the tactical changes.
                                </div>
                                <div class="message-time">2:40 PM</div>
                            </div>
                        </div>

                        <div class="message received">
                            <div class="message-avatar">
                                <img src="https://via.placeholder.com/32x32?text=CW" alt="Coach Wilson">
                            </div>
                            <div class="message-bubble">
                                <div class="message-attachment">
                                    <div class="attachment-preview">
                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                        <span>Tactical Plan - Week 9.pdf</span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary">Download</button>
                                </div>
                                <div class="message-time">2:41 PM</div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="message-input-area">
                        <div class="message-input-container">
                            <div class="message-input-row">
                                <button class="btn btn-outline-secondary message-attach-btn">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <div class="message-input-wrapper">
                                    <textarea class="form-control message-input" placeholder="Type your message..." rows="1"></textarea>
                                    <button class="btn btn-primary message-send-btn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Contact Options -->
        <div class="portal-section" data-aos="fade-up" data-aos-delay="400">
            <div class="section-header">
                <h3 class="section-title">Quick Contacts</h3>
                <p class="section-subtitle">Direct contact options for urgent matters</p>
            </div>

            <div class="quick-contacts">
                <div class="contact-item emergency" data-aos="zoom-in">
                    <div class="contact-icon">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                    </div>
                    <div class="contact-info">
                        <h5>Emergency Contact</h5>
                        <p>For medical emergencies or urgent situations</p>
                        <div class="contact-number">+254 XXX XXX XXX</div>
                    </div>
                    <div class="contact-action">
                        <button class="btn btn-danger">
                            <i class="fas fa-phone me-1"></i>Call Now
                        </button>
                    </div>
                </div>

                <div class="contact-item coach" data-aos="zoom-in" data-aos-delay="100">
                    <div class="contact-icon">
                        <i class="fas fa-user-tie text-primary"></i>
                    </div>
                    <div class="contact-info">
                        <h5>Your Coach</h5>
                        <p>Coach Wilson - Monday to Friday, 8 AM - 6 PM</p>
                        <div class="contact-number">+254 XXX XXX XXX</div>
                    </div>
                    <div class="contact-action">
                        <button class="btn btn-primary">
                            <i class="fas fa-envelope me-1"></i>Message
                        </button>
                    </div>
                </div>

                <div class="contact-item academic" data-aos="zoom-in" data-aos-delay="200">
                    <div class="contact-icon">
                        <i class="fas fa-graduation-cap text-success"></i>
                    </div>
                    <div class="contact-info">
                        <h5>Academic Advisor</h5>
                        <p>For academic support and counseling</p>
                        <div class="contact-number">+254 XXX XXX XXX</div>
                    </div>
                    <div class="contact-action">
                        <button class="btn btn-success">
                            <i class="fas fa-calendar-plus me-1"></i>Schedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Messages Sidebar */
.messages-sidebar {
    height: fit-content;
}

.messages-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.messages-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
}

.message-count {
    background: var(--danger);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.messages-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.message-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--transition-normal);
    position: relative;
}

.message-item:hover {
    background: var(--bg-tertiary);
}

.message-item.active,
.message-item.active:hover {
    background: var(--primary-red-light);
}

.message-item.unread::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 20px;
    background: var(--primary-red);
    border-radius: 2px;
}

.message-avatar {
    position: relative;
    flex-shrink: 0;
}

.message-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.message-item .message-avatar span.avatar-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-red), var(--accent-green));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
}

.message-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.message-status.online {
    background: var(--success-green);
}

.message-status.away {
    background: var(--warning-yellow);
}

.message-status.offline {
    background: var(--text-muted);
}

.message-content {
    flex: 1;
    min-width: 0;
}

.message-sender {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
    font-size: 14px;
}

.message-preview {
    color: var(--text-secondary);
    font-size: 13px;
    margin-bottom: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.message-time {
    font-size: 11px;
    color: var(--text-muted);
    text-align: right;
}

.message-indicator {
    color: var(--primary-red);
    font-size: 8px;
}

/* Announcements */
.announcements-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.announcement-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: white;
    border: var(--border-light);
    border-radius: 12px;
}

.announcement-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 50px;
}

.announcement-badge {
    width: 32px;
    height: 32px;
    background: var(--bg-tertiary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.announcement-date {
    font-size: 11px;
    color: var(--text-muted);
    font-weight: 500;
}

.announcement-content {
    flex: 1;
}

.announcement-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.announcement-text {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 0;
}

/* Message Thread */
.message-thread {
    height: 600px;
    display: flex;
    flex-direction: column;
}

.message-thread-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: var(--border);
    background: linear-gradient(135deg, var(--bg-tertiary), rgba(255, 255, 255, 0.8));
}

.thread-participant {
    display: flex;
    align-items: center;
    gap: 12px;
}

.participant-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.participant-name {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
}

.participant-role {
    font-size: 12px;
    color: var(--text-secondary);
}

.thread-actions {
    display: flex;
    gap: 8px;
}

.message-thread-content {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    max-width: 80%;
}

.message.received {
    align-self: flex-start;
}

.message.sent {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message .message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    flex-shrink: 0;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    background: var(--bg-tertiary);
    position: relative;
}

.message.sent .message-bubble {
    background: var(--primary-red);
    color: white;
}

.message.sent .message-bubble.sent {
    background: var(--primary-red);
    color: white;
}

.message-text {
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 4px;
}

.message-attachment {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    margin-bottom: 4px;
}

.attachment-preview {
    display: flex;
    align-items: center;
    font-weight: 500;
    color: var(--text-primary);
}

.message-time {
    font-size: 11px;
    color: var(--text-muted);
    text-align: right;
    margin-top: 4px;
}

.message.sent .message-time {
    text-align: left;
    color: rgba(255, 255, 255, 0.7);
}

/* Message Input */
.message-input-area {
    border-top: var(--border);
    background: white;
    padding: 16px 20px;
}

.message-input-container {
    max-width: none;
}

.message-input-row {
    display: flex;
    align-items: flex-end;
    gap: 12px;
}

.message-attach-btn {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.message-input-wrapper {
    flex: 1;
    display: flex;
    gap: 12px;
    align-items: flex-end;
}

.message-input {
    flex: 1;
    border: var(--border);
    border-radius: 22px;
    padding: 12px 16px;
    resize: none;
    min-height: 44px;
    max-height: 100px;
}

.message-send-btn {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Quick Contacts */
.quick-contacts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: white;
    border: var(--border-light);
    border-radius: 16px;
    transition: var(--transition-normal);
}

.contact-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.contact-item.emergency {
    border-left: 4px solid var(--danger);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), white);
}

.contact-item.coach {
    border-left: 4px solid var(--primary-red);
    background: linear-gradient(135deg, rgba(234, 28, 77, 0.05), white);
}

.contact-item.academic {
    border-left: 4px solid var(--accent-green);
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), white);
}

.contact-icon {
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

.contact-info {
    flex: 1;
}

.contact-info h5 {
    margin-bottom: 4px;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
}

.contact-info p {
    margin-bottom: 4px;
    font-size: 14px;
    color: var(--text-secondary);
}

.contact-number {
    font-weight: 600;
    color: var(--accent-blue);
}

.contact-action {
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .message-item {
        padding: 10px 12px;
    }

    .message-thread-header {
        padding: 16px;
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .message.sent,
    .message.received {
        max-width: 85%;
    }

    .message-input-row {
        flex-direction: column;
        gap: 8px;
    }

    .message-attach-btn,
    .message-send-btn {
        width: 40px;
        height: 40px;
    }

    .quick-contacts {
        grid-template-columns: 1fr;
    }

    .contact-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize message input
    const messageInput = document.querySelector('.message-input');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
    }

    // Scroll to bottom of message thread
    const messageThread = document.querySelector('.message-thread-content');
    if (messageThread) {
        messageThread.scrollTop = messageThread.scrollHeight;
    }

    // Message item click handler
    document.querySelectorAll('.message-item').forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            document.querySelectorAll('.message-item').forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            // Remove unread indicator
            this.classList.remove('unread');
        });
    });
});
</script>
@endpush
@endsection
