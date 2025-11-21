<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storm Breaker - Version Selector</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            width: 100%;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header i {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .header h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .version-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            animation: slideUp 1s;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .version-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .version-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.4);
        }

        .version-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .version-badge.new {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .version-card h2 {
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .version-card h2 i {
            font-size: 2.5rem;
        }

        .version-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .features {
            list-style: none;
            margin-bottom: 30px;
        }

        .features li {
            padding: 8px 0;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .features li i {
            color: #2ecc71;
            font-size: 1.1rem;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(127, 140, 141, 0.4);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(127, 140, 141, 0.6);
        }

        .footer {
            text-align: center;
            color: white;
            margin-top: 50px;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .version-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-bolt"></i>
            <h1>Storm Breaker</h1>
            <p>Choose Your Version</p>
        </div>

        <div class="version-cards">
            <!-- Version 4 Card -->
            <div class="version-card">
                <span class="version-badge new">NEW! ⚡</span>
                <h2>
                    <i class="fas fa-rocket"></i>
                    Version 4.0
                </h2>
                <p>Enhanced Edition with modern UI and powerful new features</p>
                
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Modern Dashboard UI</li>
                    <li><i class="fas fa-check-circle"></i> Real-Time Statistics</li>
                    <li><i class="fas fa-check-circle"></i> Dark Mode Support</li>
                    <li><i class="fas fa-check-circle"></i> Advanced Filtering</li>
                    <li><i class="fas fa-check-circle"></i> Export as JSON/TXT</li>
                    <li><i class="fas fa-check-circle"></i> Session Tracking</li>
                    <li><i class="fas fa-check-circle"></i> Mobile Responsive</li>
                </ul>

                <a href="login-v4.php" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> Launch V4
                </a>
            </div>

            <!-- Version 3 Card -->
            <div class="version-card">
                <span class="version-badge">Classic</span>
                <h2>
                    <i class="fas fa-shield-alt"></i>
                    Version 3.0
                </h2>
                <p>Original stable version with core functionality</p>
                
                <ul class="features">
                    <li><i class="fas fa-check-circle"></i> Proven Stability</li>
                    <li><i class="fas fa-check-circle"></i> Simple Interface</li>
                    <li><i class="fas fa-check-circle"></i> Fast & Lightweight</li>
                    <li><i class="fas fa-check-circle"></i> All Core Features</li>
                    <li><i class="fas fa-check-circle"></i> Template Support</li>
                    <li><i class="fas fa-check-circle"></i> Basic Logging</li>
                </ul>

                <a href="login.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> Launch V3
                </a>
            </div>
        </div>

        <div class="footer">
            <p>
                <i class="fas fa-info-circle"></i> 
                For setup instructions and documentation, see 
                <a href="https://github.com/ultrasecurity/Storm-Breaker" style="color: white; text-decoration: underline;">README.md</a>
            </p>
            <p style="margin-top: 10px;">
                <i class="fas fa-book"></i> 
                V4 Features Guide: UPGRADE_NOTES.md
            </p>
        </div>
    </div>

    <script>
        // Add subtle parallax effect
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.version-card');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            cards.forEach(card => {
                const depth = 10;
                const moveX = (x - 0.5) * depth;
                const moveY = (y - 0.5) * depth;
                card.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
        });
    </script>
</body>
</html>
