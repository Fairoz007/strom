<?php
session_start();
include "config.php";
include "./assets/components/login-arc.php";



if(isset($_COOKIE['logindata']) && $_COOKIE['logindata'] == $key['token'] && $key['expired'] == "no"){
    $_SESSION['IAm-logined'] = 'yes';
	header("location: panel-v4.php");
}


elseif(isset($_SESSION['IAm-logined'])){
	header('location: panel-v4.php');
	exit;
}


else{ 
	
	?>


    <!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Storm Breaker V4 - Login</title>
		<link href="./assets/css/bootstrap.min.css" rel="stylesheet">
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

			.login-container {
				background: rgba(255, 255, 255, 0.95);
				backdrop-filter: blur(10px);
				border-radius: 20px;
				box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
				overflow: hidden;
				max-width: 450px;
				width: 100%;
				animation: slideIn 0.5s ease-out;
			}

			@keyframes slideIn {
				from {
					opacity: 0;
					transform: translateY(-50px);
				}
				to {
					opacity: 1;
					transform: translateY(0);
				}
			}

			.login-header {
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				padding: 40px 30px;
				text-align: center;
				color: white;
			}

			.login-header i {
				font-size: 4rem;
				margin-bottom: 15px;
				animation: pulse 2s infinite;
			}

			@keyframes pulse {
				0%, 100% {
					transform: scale(1);
				}
				50% {
					transform: scale(1.1);
				}
			}

			.login-header h1 {
				font-size: 2rem;
				font-weight: bold;
				margin-bottom: 5px;
			}

			.login-header p {
				opacity: 0.9;
				font-size: 0.95rem;
			}

			.login-body {
				padding: 40px 30px;
			}

			.input-group {
				position: relative;
				margin-bottom: 25px;
			}

			.input-group i {
				position: absolute;
				left: 15px;
				top: 50%;
				transform: translateY(-50%);
				color: #667eea;
				font-size: 1.2rem;
			}

			.input-group input {
				width: 100%;
				padding: 15px 15px 15px 45px;
				border: 2px solid #e0e0e0;
				border-radius: 10px;
				font-size: 1rem;
				transition: all 0.3s ease;
			}

			.input-group input:focus {
				outline: none;
				border-color: #667eea;
				box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
			}

			.btn-login {
				width: 100%;
				padding: 15px;
				background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
				border: none;
				border-radius: 10px;
				color: white;
				font-size: 1.1rem;
				font-weight: 600;
				cursor: pointer;
				transition: all 0.3s ease;
				box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
			}

			.btn-login:hover {
				transform: translateY(-2px);
				box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
			}

			.btn-login:active {
				transform: translateY(0);
			}

			.error-message {
				background: #ffe0e0;
				color: #c0392b;
				padding: 12px;
				border-radius: 8px;
				margin-bottom: 20px;
				border-left: 4px solid #e74c3c;
				display: flex;
				align-items: center;
				animation: shake 0.5s;
			}

			@keyframes shake {
				0%, 100% { transform: translateX(0); }
				25% { transform: translateX(-10px); }
				75% { transform: translateX(10px); }
			}

			.error-message i {
				margin-right: 10px;
				font-size: 1.2rem;
			}

			.login-footer {
				text-align: center;
				padding: 20px;
				background: #f8f9fa;
				color: #666;
				font-size: 0.9rem;
			}

			.login-footer a {
				color: #667eea;
				text-decoration: none;
				font-weight: 600;
			}

			.remember-me {
				display: flex;
				align-items: center;
				margin-bottom: 20px;
				font-size: 0.95rem;
			}

			.remember-me input {
				margin-right: 8px;
				width: 18px;
				height: 18px;
				cursor: pointer;
			}

			@media (max-width: 480px) {
				.login-header h1 {
					font-size: 1.5rem;
				}
				
				.login-body {
					padding: 30px 20px;
				}
			}
		</style>
	</head>

	<body>
		<div class="login-container">
			<div class="login-header">
				<i class="fas fa-bolt"></i>
				<h1>Storm Breaker V4</h1>
				<p>Security Testing Platform</p>
			</div>

			<div class="login-body">
				<form action="" method="POST">
					<?php
					if($_SERVER['REQUEST_METHOD'] == 'POST'){
						if(isset($_POST['username']) && isset($_POST['password'])){
							$username = $_POST['username'];
							$password = $_POST['password'];

							if(isset($CONFIG[$username]) && $CONFIG[$username]['password'] == $password){
								$_SESSION['IAm-logined'] = $username;
								echo '<script>window.location.href="panel-v4.php"</script>';
							}else{
								echo '<div class="error-message">
										<i class="fas fa-exclamation-circle"></i>
										<span>Invalid username or password!</span>
									  </div>';
							}
						}
					}
					?>

					<div class="input-group">
						<i class="fas fa-user"></i>
						<input type="text" name="username" placeholder="Username" required autofocus>
					</div>

					<div class="input-group">
						<i class="fas fa-lock"></i>
						<input type="password" name="password" placeholder="Password" required>
					</div>

					<div class="remember-me">
						<input type="checkbox" id="remember" name="remember">
						<label for="remember">Remember me</label>
					</div>

					<button type="submit" class="btn-login">
						<i class="fas fa-sign-in-alt"></i> Login
					</button>
				</form>
			</div>

			<div class="login-footer">
				<p>Default credentials: <strong>admin / admin</strong></p>
				<p style="margin-top: 10px; font-size: 0.85rem;">
					<i class="fas fa-shield-alt"></i> Secured Connection
				</p>
			</div>
		</div>

		<script>
			// Add subtle animations on input focus
			document.querySelectorAll('.input-group input').forEach(input => {
				input.addEventListener('focus', function() {
					this.parentElement.querySelector('i').style.transform = 'translateY(-50%) scale(1.2)';
				});
				input.addEventListener('blur', function() {
					this.parentElement.querySelector('i').style.transform = 'translateY(-50%) scale(1)';
				});
			});
		</script>
	</body>
	</html>



	<?php
}

?>
