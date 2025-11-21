// Enhanced Features for Storm Breaker V4
var sessionStartTime = new Date();
var logCount = 0;
var recentActivityCount = 0;
var fullLogData = [];
var listenerActive = true;

// Initialize stats on page load
function initializeStats() {
    // Load saved log count
    const savedLogs = localStorage.getItem('total_logs');
    if (savedLogs) {
        logCount = parseInt(savedLogs);
    }
    
    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark-mode');
        document.getElementById('theme-icon').className = 'fas fa-sun';
    }
    
    updateStats();
    
    // Start session timer
    setInterval(updateSessionTime, 1000);
}

// Dark Mode Toggle
function toggleTheme() {
    document.body.classList.toggle('dark-mode');
    const icon = document.getElementById('theme-icon');
    if (document.body.classList.contains('dark-mode')) {
        icon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
    } else {
        icon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
    }
}

// Update Statistics
function updateStats() {
    // Update total links - count after DOM is ready
    setTimeout(function() {
        const linkCount = $('.link-card').length || document.querySelectorAll('.link-card').length;
        $('#total-links').text(linkCount);
    }, 500);
    
    // Update total logs
    $('#total-logs').text(logCount);
    
    // Update session time
    updateSessionTime();
    
    // Update recent activity
    $('#recent-activity').text(recentActivityCount);
}

function updateSessionTime() {
    const now = new Date();
    const diff = Math.floor((now - sessionStartTime) / 1000);
    const hours = Math.floor(diff / 3600);
    const minutes = Math.floor((diff % 3600) / 60);
    const seconds = diff % 60;
    
    if (hours > 0) {
        $('#session-time').text(
            String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0')
        );
    } else {
        $('#session-time').text(
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0')
        );
    }
}

// Copy all logs
function copyAllLogs() {
    const text = $('#result').val();
    if (!text) {
        Swal.fire({
            icon: 'warning',
            title: 'No logs to copy',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'All logs copied to clipboard',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(function() {
        // Fallback for older browsers
        const textarea = document.getElementById('result');
        textarea.select();
        document.execCommand('copy');
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            timer: 2000,
            showConfirmButton: false
        });
    });
}

// Export as JSON
function exportJSON() {
    const logs = $('#result').val();
    if (!logs) {
        Swal.fire({
            icon: 'warning',
            title: 'No logs to export',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    
    const data = {
        timestamp: new Date().toISOString(),
        session_duration: $('#session-time').text(),
        total_logs: logCount,
        recent_activity: recentActivityCount,
        logs: logs.split('\n-------------------------\n').filter(l => l.trim())
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'stormbreaker_logs_' + Date.now() + '.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    
    Swal.fire({
        icon: 'success',
        title: 'Exported!',
        text: 'Logs exported as JSON',
        timer: 2000,
        showConfirmButton: false
    });
}

// Apply Filter
function applyFilter() {
    const searchTerm = $('#search-logs').val().toLowerCase();
    const filterType = $('#filter-type').val();
    
    if (searchTerm === '' && filterType === 'all') {
        $('#result').val(old_data);
        return;
    }
    
    const lines = old_data.split('\n-------------------------\n');
    let filtered = lines;
    
    if (searchTerm) {
        filtered = filtered.filter(line => line.toLowerCase().includes(searchTerm));
    }
    
    if (filterType !== 'all') {
        filtered = filtered.filter(line => {
            const lower = line.toLowerCase();
            if (filterType === 'location') return lower.includes('google map') || lower.includes('location');
            if (filterType === 'image') return lower.includes('image');
            if (filterType === 'audio') return lower.includes('audio');
            if (filterType === 'device') return lower.includes('device') || lower.includes('browser');
            return true;
        });
    }
    
    $('#result').val(filtered.join('\n-------------------------\n'));
    
    Swal.fire({
        icon: 'info',
        title: 'Filter Applied',
        text: `Found ${filtered.length} matching entries`,
        timer: 2000,
        showConfirmButton: false
    });
}

// Refresh Stats
function refreshStats() {
    updateStats();
    Swal.fire({
        icon: 'info',
        title: 'Stats Refreshed',
        timer: 1500,
        showConfirmButton: false
    });
}

// Update listener status
function updateListenerStatus(isRunning) {
    const badge = $('#listener-status');
    listenerActive = isRunning;
    if (isRunning) {
        badge.removeClass('badge-offline').addClass('badge-online');
        badge.html('<i class="fas fa-circle"></i> Listener Active');
    } else {
        badge.removeClass('badge-online').addClass('badge-offline');
        badge.html('<i class="fas fa-circle"></i> Listener Stopped');
    }
}

// Enhanced listener tracking
function trackNewLog(data) {
    logCount++;
    recentActivityCount++;
    localStorage.setItem('total_logs', logCount);
    fullLogData.push({
        timestamp: new Date().toISOString(),
        data: data
    });
    updateStats();
    
    // Reset recent activity after 5 seconds
    setTimeout(() => {
        recentActivityCount = Math.max(0, recentActivityCount - 1);
        updateStats();
    }, 5000);
}

// Clear logs with confirmation
$('#btn-clear').click(function() {
    Swal.fire({
        title: 'Clear All Logs?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, clear it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#result').val('');
            old_data = '';
            logCount = 0;
            recentActivityCount = 0;
            fullLogData = [];
            localStorage.setItem('total_logs', '0');
            updateStats();
            Swal.fire({
                icon: 'success',
                title: 'Cleared!',
                text: 'All logs have been cleared',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
});

// Update listener button functionality
$(document).ready(function() {
    // Override the original listener button handler
    $('#btn-listen').off('click').on('click', function() {
        if (listenerActive) {
            clearInterval(timer);
            listenerActive = false;
            updateListenerStatus(false);
            $(this).html('<i class="fas fa-play"></i> Start Listener');
            $(this).removeClass('btn-danger-modern').addClass('btn-success-modern');
        } else {
            timer = setInterval(Listener, 2000);
            listenerActive = true;
            updateListenerStatus(true);
            $(this).html('<i class="fas fa-stop"></i> Stop Listener');
            $(this).removeClass('btn-success-modern').addClass('btn-danger-modern');
        }
    });
    
    // Update stats when links are loaded
    setTimeout(updateStats, 1000);
});

// Override the original Listener function to track logs
var originalListener = window.Listener;
if (typeof Listener === 'function') {
    window.Listener = function() {
        $.post("receiver.php", {"send_me_result": ""}, function(data) {
            if (data != "") {
                trackNewLog(data);
                
                if (data.includes("Image")) {
                    show_notif("Image File Saved", 'Path : ' + data.slice(26), true);
                } else if (data.includes("Audio")) {
                    show_notif("Audio File Saved", 'Path : ' + data.slice(26), false);
                } else if (data.includes("Google Map")) {
                    show_notif("Google Map Link", data.slice(18), true);
                }

                old_data += data + "\n-------------------------\n";
                $("#result").val(old_data);
            }
        });
    };
}
