<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clean & Modern Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0f172a; /* Navy Blue */
            --accent-color: #2563eb;  /* Business Blue */
            --bg-body: #f8fafc;
            --text-muted: #64748b;
        }

        body {
            margin: 0;
            height: 100vh;
            background-color: var(--bg-body);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-container {
            width: 850px;
            height: 550px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            overflow: hidden;
            position: relative;
            border: 1px solid #e2e8f0;
        }

        /* Forms Layout */
        .form-section {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            padding: 50px;
            transition: all 0.6s ease-in-out;
        }

        .login-section { left: 0; z-index: 2; }
        .register-section { left: 0; opacity: 0; z-index: 1; }

        /* Animation Classes */
        .auth-container.active .login-section { transform: translateX(100%); opacity: 0; }
        .auth-container.active .register-section { transform: translateX(100%); opacity: 1; z-index: 5; }

        /* Toggle Panel - Right Side */
        .toggle-panel {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            background: var(--primary-color);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            transition: transform 0.6s ease-in-out;
            z-index: 10;
        }

        .auth-container.active .toggle-panel {
            transform: translateX(-100%);
        }

        /* Form Elements */
        h2 { font-weight: 700; color: var(--primary-color); }
        h4 { font-weight: 600; }
        p { font-size: 0.95rem; color: var(--text-muted); }

        .form-label { font-weight: 500; font-size: 0.85rem; color: var(--primary-color); }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            background: #f1f5f9;
            font-size: 0.9rem;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-main {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-outline-custom {
            border: 2px solid rgba(255,255,255,0.3);
            background: transparent;
            color: white;
            padding: 10px 35px;
            border-radius: 30px;
            margin-top: 20px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-color);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-container { width: 90%; height: auto; padding: 20px; }
            .toggle-panel { display: none; }
            .form-section { position: relative; width: 100%; padding: 20px; }
            .register-section { display: none; }
            .auth-container.active .register-section { display: block; opacity: 1; transform: none; }
        }
    </style>
</head>
<body>

<div class="auth-container" id="authBox">
    
    <div class="form-section login-section">
        <h2>Sign In</h2>
        <p class="mb-4">Enter your account details.</p>
        <form>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" placeholder="name@company.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <div class="d-flex justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
                <a href="#" class="small text-decoration-none" style="color: var(--accent-color)">Forgot?</a>
            </div>
            <button type="button" class="btn btn-main w-100">Login to Dashboard</button>
        </form>
    </div>

    <div class="form-section register-section">
        <h2>Create Account</h2>
        <p class="mb-4">Start your journey with us.</p>
        <form>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" placeholder="John Doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Work Email</label>
                <input type="email" class="form-control" placeholder="name@company.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" placeholder="••••••••">
            </div>
            <button type="button" class="btn btn-main w-100">Create Account</button>
        </form>
    </div>

    <div class="toggle-panel text-center" id="togglePanel">
        <div class="toggle-content text-center">
            <h4 id="toggleTitle">New Here?</h4>
            <p class="text-white-50" id="toggleDesc">Register to explore more features of our platform.</p>
            <button class="btn btn-outline-custom" onclick="toggleForm()">Switch to Register</button>
        </div>
    </div>

</div>

<script>
    let isLogin = true;
    function toggleForm() {
        const box = document.getElementById('authBox');
        const title = document.getElementById('toggleTitle');
        const desc = document.getElementById('toggleDesc');
        const btn = document.querySelector('.btn-outline-custom');

        box.classList.toggle("active");
        isLogin = !isLogin;

        if(!isLogin) {
            title.innerText = "Welcome Back!";
            desc.innerText = "To stay connected with us, please login with your personal info.";
            btn.innerText = "Switch to Login";
        } else {
            title.innerText = "New Here?";
            desc.innerText = "Register to explore more features of our platform.";
            btn.innerText = "Switch to Register";
        }
    }
</script>

</body>
</html>