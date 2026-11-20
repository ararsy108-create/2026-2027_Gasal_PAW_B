<?php
	session_start();
	include 'koneksi.php';

	//jika user sudah login, maka tidak boleh mengakses halaman login
	if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
	    header("Location: index.php");
	    exit;
	}

	$error = "";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    $username = trim($_POST['username'] ?? '');
	    $password = $_POST['password'] ?? '';

	    if ($username === '' || $password === '') {
	        $error = "Username dan password wajib diisi.";
	    } else {
	        $stmt = mysqli_prepare($conn, "SELECT id, username, password, nama, level FROM usser WHERE username = ? LIMIT 1");
	        mysqli_stmt_bind_param($stmt, "s", $username);
	        mysqli_stmt_execute($stmt);
	        $result = mysqli_stmt_get_result($stmt);
	        $user = mysqli_fetch_assoc($result);

	        // Mendukung password hash maupun plaintext (seperti pada data awal)
	        $validPassword = false;
	        if ($user) {
	            if (password_verify($password, $user['password']) || $password === $user['password']) {
	                $validPassword = true;
	            }
	        }

	        if ($user && $validPassword) {
	            session_regenerate_id(true);
	            $_SESSION['login']    = true;
	            $_SESSION['user_id']  = $user['id'];
	            $_SESSION['username'] = $user['username'];
	            $_SESSION['nama']     = $user['nama'];
	            $_SESSION['level']    = (int) $user['level']; // 1 = owner, 2 = kasir

	            header("Location: index.php");
	            exit;
	        } else {
	            $error = "Username atau password salah.";
	        }
	    }
	}
?>
<!DOCTYPE html>
<html>
	<head>
	    <title>Login - Sistem Penjualan</title>
	    <style>
	        body { 
	        	font-family: Arial, sans-serif; 
	        	background: #f4f6f9; 
	        	display: flex; 
	        	justify-content: center; 
	        	align-items: center; 
	        	height: 100vh; 
	        	margin: 0; 
	        }
	        .login-box { 
	        	background: #fff; 
	        	padding: 24px; 
	        	border-radius: 6px; 
	        	width: 320px; 
	        	box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
	        }
	        .login-box h2 { 
	        	margin-top: 0; 
	        	color: #0b4f8a; 
	        }
	        .form-group { 
	        	margin-bottom: 14px; 
	        }
	        label { 
	        	display: block; 
	        	margin-bottom: 5px; 
	        	font-size: 14px; 
	        }
	        input[type="text"], input[type="password"] {
	        	width: 100%; 
	        	padding: 8px; 
	        	box-sizing: border-box; 
	        	border: 1px solid #ccc; 
	        	border-radius: 4px; 
	        }
	        button { 
	        	width: 100%; 
	        	padding: 10px; 
	        	background: #0b4f8a; 
	        	color: #fff; 
	        	border: none; 
	        	border-radius: 4px; 
	        	cursor: pointer; 
	        	font-weight: bold; 
	        }
	        .error { 
	        	background: #fdecea; 
	        	color: #b71c1c; 
	        	padding: 8px; 
	        	border-radius: 4px; 
	        	margin-bottom: 12px; 
	        	font-size: 13px; 
	        }
	    </style>
	</head>
	<body>
	    <div class="login-box">
	        <h2>Login</h2>
	        <?php if ($error !== ""): ?>
	            <div class="error"><?= htmlspecialchars($error) ?></div>
	        <?php endif; ?>
	        <form method="POST" action="">
	            <div class="form-group">
	                <label for="username">Username</label>
	                <input type="text" id="username" name="username" required>
	            </div>
	            <div class="form-group">
	                <label for="password">Password</label>
	                <input type="password" id="password" name="password" required>
	            </div>
	            <button type="submit">Masuk</button>
	        </form>
	    </div>
	</body>
</html>