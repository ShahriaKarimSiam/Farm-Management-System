<?php

session_start();

include "config.php";

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT user_id, username, password, full_name
         FROM users
         WHERE username = ?"
    );

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];

            header("Location: index.php");
            exit;

        } else {

            $error = "Invalid username or password.";

        }

    } else {

        $error = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Farm Management System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: url('farm_bg.jpg') no-repeat center center / cover;
    position: relative;
}

body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0, 30, 10, 0.45);
    backdrop-filter: blur(1px);
}

.login-card {
    position: relative;
    z-index: 1;
    width: 420px;
    padding: 50px 40px 40px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
    color: white;
    animation: fadeUp 0.6s ease;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

.login-title {
    text-align: center;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: 1px;
}

.login-subtitle {
    text-align: center;
    font-size: 13px;
    color: rgba(255,255,255,0.65);
    margin-bottom: 35px;
    letter-spacing: 0.5px;
}

.input-group {
    position: relative;
    margin-bottom: 18px;
}

.input-group input {
    width: 100%;
    padding: 14px 46px 14px 18px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    color: white;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: border 0.3s, background 0.3s;
}

.input-group input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.input-group input:focus {
    border-color: rgba(255, 255, 255, 0.7);
    background: rgba(255, 255, 255, 0.22);
}

.input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    display: flex;
    align-items: center;
    pointer-events: none;
}

.input-icon svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* Password toggle */
.toggle-pwd-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: rgba(255,255,255,0.6);
    display: flex;
    align-items: center;
    padding: 0;
    transition: color 0.2s;
}

.toggle-pwd-btn:hover {
    color: white;
}

.toggle-pwd-btn svg {
    width: 18px;
    height: 18px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

/* Error */
.error-msg {
    background: rgba(220, 38, 38, 0.35);
    border: 1px solid rgba(239, 68, 68, 0.5);
    color: #fecaca;
    padding: 11px 16px;
    border-radius: 10px;
    font-size: 13px;
    text-align: center;
    margin-bottom: 18px;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 14px;
    margin-top: 8px;
    background: white;
    color: #166534;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    letter-spacing: 0.5px;
    transition: background 0.3s, color 0.3s, transform 0.15s;
}

.login-btn:hover {
    background: #f0fdf4;
    transform: translateY(-1px);
}

.login-btn:active {
    transform: translateY(0);
}

.login-footer {
    text-align: center;
    margin-top: 22px;
    font-size: 13px;
    color: rgba(255,255,255,0.6);
}

.login-footer span {
    color: white;
    font-weight: 500;
}

/* Stars particle effect */
.star {
    position: fixed;
    width: 3px;
    height: 3px;
    background: rgba(255,255,255,0.7);
    border-radius: 50%;
    animation: twinkle 3s infinite alternate;
    z-index: 0;
}

@keyframes twinkle {
    from { opacity: 0.2; transform: scale(0.8); }
    to   { opacity: 1;   transform: scale(1.2); }
}

</style>
</head>

<body>

<div class="login-card">

    <div class="login-title">Login</div>
    <div class="login-subtitle">🌾 Farm Management System</div>

    <?php if ($error != "") { ?>
    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <form method="POST">

        <!-- Username -->
        <div class="input-group">
            <input
                type="text"
                name="username"
                placeholder="Username"
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                required
                autocomplete="username"
            >
            <span class="input-icon">
                <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
        </div>

        <!-- Password -->
        <div class="input-group">
            <input
                type="password"
                name="password"
                id="passwordField"
                placeholder="Password"
                required
                autocomplete="current-password"
            >
            <button type="button" class="toggle-pwd-btn" id="togglePwd" aria-label="Toggle password visibility">
                <svg id="eyeIcon" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </button>
        </div>

        <button type="submit" class="login-btn">Login</button>

    </form>

    <div class="login-footer">
        Farm Management &mdash; <span>Admin Portal</span>
    </div>

</div>

<script>
(function () {
    var btn   = document.getElementById('togglePwd');
    var input = document.getElementById('passwordField');
    var eyeOpen   = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
    var eyeClosed = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';

    btn.addEventListener('click', function () {
        if (input.type === 'password') {
            input.type = 'text';
            document.getElementById('eyeIcon').innerHTML = eyeClosed;
        } else {
            input.type = 'password';
            document.getElementById('eyeIcon').innerHTML = eyeOpen;
        }
    });
})();
</script>

</body>
</html>