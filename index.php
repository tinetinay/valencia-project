<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &bull; Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 20px;
        }

        .log-box {
            background: #ffffff;
            width: 100%;
            max-width: 380px;
            padding: 45px 35px;
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .log-box h2 {
            margin-bottom: 8px;
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 800;
        }

        .log-box p.subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 28px;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        input {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 50px;
            outline: none;
            font-size: 0.95rem;
            background: #f8fafc;
            color: #1e293b;
            transition: all 0.25s ease;
        }

        input::placeholder {
            color: #94a3b8;
        }

        input:focus {
            border-color: #38bdf8;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        }

        button {
            width: 100%;
            padding: 14px;
            margin-top: 10px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        }

        button:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
        }

        .footer-link {
            margin-top: 25px;
            color: #64748b;
            font-size: 0.88rem;
        }

        .footer-link a {
            color: #0284c7;
            text-decoration: none;
            font-weight: 700;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="log-box">
        <h2>Welcome back</h2>
        <p class="subtitle">Enter your credentials to continue</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <form action="pakicheck.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required autofocus>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit">LOGIN</button>
        </form>

        <p class="footer-link">
            Don't have an account? <a href="#">Sign Up</a>
        </p>
    </div>

</body>
</html>