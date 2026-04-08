<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រព័ន្ធគ្រប់គ្រងការលក់ | ចូលប្រើប្រាស់</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --accent-color: #2563eb;
            --bg-body: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            margin: 0;
            min-height: 100vh;
            /* background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.4)),
                url("{{ asset('Image/stores/bg.jpg') }}") center/cover no-repeat fixed; */
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Kantumruy Pro', sans-serif;
        }

        .auth-container {
            width: 950px;
            height: 650px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
        }

        .form-section {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            padding: 50px 60px;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-section {
            left: 0;
            z-index: 2;
        }

        .register-section {
            left: 0;
            opacity: 0;
            z-index: 1;
        }

        .auth-container.active .login-section {
            transform: translateX(100%);
            opacity: 0;
            z-index: 1;
        }

        .auth-container.active .register-section {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
        }

        .toggle-panel {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10;
        }

        .auth-container.active .toggle-panel {
            transform: translateX(-100%);
        }

        h2 {
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .brand-logo {
            color: var(--accent-color);
            font-size: 1.6rem;
            margin-bottom: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.85rem;
            margin-bottom: 6px;
        }

        .input-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            transition: 0.3s;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding-left: 15px;
        }

        .form-control {
            border: none;
            background: transparent;
            height: 48px;
            font-size: 0.95rem;
            box-shadow: none !important;
        }

        .input-group:focus-within {
            border-color: var(--accent-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* បែបផែនពេលមាន Error */
        .input-group.has-error {
            border-color: #f43f5e !important;
            background-color: #fff1f2 !important;
        }

        .error-text {
            color: #e11d48;
            font-size: 0.75rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }

        .btn-main {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2);
            color: white;
        }

        .btn-outline-custom {
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 40px;
            border-radius: 14px;
            margin-top: 25px;
            font-weight: 500;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: #0f172a;
        }

        @media (max-width: 768px) {
            .auth-container {
                width: 95%;
                height: auto;
                display: block;
            }

            .toggle-panel {
                display: none;
            }

            .form-section {
                position: relative;
                width: 100%;
                padding: 40px 25px;
            }
        }

        /* បន្ថែម ឬជំនួសក្នុងផ្នែក <style> */

        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            --accent-color: #3b82f6;
            --bg-body: #f1f5f9;
        }

        /* កែសម្រួល auth-container ឱ្យសមស្របនឹង Screen ច្រើនប្រភេទ */
        .auth-container {
            width: 1000px;
            /* រាងធំជាងមុនបន្តិចឱ្យមើលទៅស្រឡះ */
            max-width: 95%;
            min-height: 600px;
            height: auto;
            background: rgba(255, 255, 255, 1);
            border-radius: 28px;
            display: flex;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* កែសម្រួល Toggle Panel ឱ្យមានភាពទាក់ទាញជាងមុន */
        .toggle-panel {
            /* background-image: url("https://www.transparenttextures.com/patterns/cubes.png"), var(--primary-gradient); */
            border-radius: 0 24px 24px 0;
        }

        .auth-container.active .toggle-panel {
            border-radius: 24px 0 0 24px;
        }

        /* បន្ថែម Style សម្រាប់ Mobile ឱ្យអាច switch បាន (សំខាន់ខ្លាំង) */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                height: auto;
                min-height: unset;
                margin: 20px 0;
            }

            .form-section {
                position: relative;
                width: 100% !important;
                transform: none !important;
                opacity: 1 !important;
                display: none;
                /* លាក់មួយ សិន */
                padding: 40px 20px;
            }

            /* បង្ហាញ Form តាមស្ថានភាព active */
            .auth-container:not(.active) .login-section {
                display: flex;
            }

            .auth-container.active .register-section {
                display: flex;
            }

            .toggle-panel {
                position: relative;
                left: 0 !important;
                width: 100% !important;
                height: auto !important;
                transform: none !important;
                padding: 30px 20px;
                order: 2;
                /* ដាក់ឱ្យនៅខាងក្រោមគេបង្អស់ */
                border-radius: 0 0 24px 24px !important;
            }
        }

        /* កែសម្រួល Input ឱ្យមើលទៅ Premium */
        .input-group {
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .input-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
    </style>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --accent-color: #2563eb;
            --bg-body: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }



        /* កែសម្រួលទំហំឱ្យតូចជាងមុន (ពី 950px មក 850px និងកម្ពស់មក 580px) */
        .auth-container {
            width: 850px;
            height: 580px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
        }

        .form-section {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            padding: 40px 45px;
            /* បន្ថយ padding ឱ្យសមនឹងទំហំថ្មី */
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* រក្សា Logic ដដែល */
        .login-section {
            left: 0;
            z-index: 2;
        }

        .register-section {
            left: 0;
            opacity: 0;
            z-index: 1;
        }

        .auth-container.active .login-section {
            transform: translateX(100%);
            opacity: 0;
        }

        .auth-container.active .register-section {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
        }

        .toggle-panel {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            transition: all 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 10;
        }

        .auth-container.active .toggle-panel {
            transform: translateX(-100%);
        }

        /* កែសម្រួលទំហំអក្សរ និង Input ឱ្យរាងស្រឡះជាងមុន */
        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .brand-logo {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
        }

        .form-control {
            height: 44px;
            /* បន្ថយ កម្ពស់ input បន្តិច */
            font-size: 0.9rem;
        }

        .btn-main {
            padding: 10px;
            font-size: 0.95rem;
        }

        /* Responsive សម្រាប់ Mobile */
        @media (max-width: 768px) {
            .auth-container {
                width: 100%;
                height: auto;
                display: flex;
                flex-direction: column;
            }

            .toggle-panel {
                display: none;
            }

            .form-section {
                position: relative;
                width: 100%;
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-container {{ $errors->has('name') || session('is_register') ? 'active' : '' }}" id="authBox">

        <div class="form-section login-section">
            <div class="brand-logo"><i class="bi bi-shield-lock-fill"></i> POS SYSTEM</div>
            <h2>ស្វាគមន៍មកវិញ</h2>
            <p class="mb-4 text-muted small">សូមបញ្ចូលព័ត៌មានរបស់អ្នកដើម្បីចូលប្រើប្រាស់។</p>

            @if (session('error'))
                <div class="alert alert-danger py-2 border-0 small mb-3"
                    style="border-radius: 10px; background: #fff1f2; color: #e11d48;">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">អ៊ីមែល</label>
                    <div class="input-group @error('email') has-error @enderror">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="បញ្ចូលអ៊ីមែល"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="error-text"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">លេខសម្ងាត់</label>
                    <div class="input-group @error('password') has-error @enderror">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="login_pass" class="form-control"
                            placeholder="••••••••" required>
                        <button class="input-group-text bg-transparent" type="button"
                            onclick="togglePassword('login_pass', 'eye_login')">
                            <i class="bi bi-eye-slash" id="eye_login"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">ចងចាំខ្ញុំ</label>
                    </div>
                    <a href="#" class="small text-decoration-none fw-semibold text-primary">ភ្លេចលេខសម្ងាត់?</a>
                </div>

                <button type="submit" class="btn btn-main w-100">ចូលប្រើប្រាស់ <i
                        class="bi bi-arrow-right ms-2"></i></button>
            </form>
        </div>

        <div class="form-section register-section">
            <div class="brand-logo"><i class="bi bi-person-plus-fill"></i> បង្កើតគណនី</div>
            <h2>ចុះឈ្មោះថ្មី</h2>
            <p class="mb-3 text-muted small">បំពេញព័ត៌មានខាងក្រោមដើម្បីចាប់ផ្ដើមប្រើប្រាស់។</p>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="mb-2">
                    <label class="form-label">ឈ្មោះពេញ</label>
                    <div class="input-group @error('name') has-error @enderror">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="ឈ្មោះរបស់អ្នក"
                            value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <div class="error-text"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">អ៊ីមែលការងារ</label>
                    <div class="input-group @error('email') has-error @enderror">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="example@mail.com"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <div class="error-text"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-2">
                    <label class="form-label">លេខសម្ងាត់</label>
                    <div class="input-group @error('password') has-error @enderror">
                        <span class="input-group-text"><i class="bi bi-key"></i></span>
                        <input type="password" name="password" id="reg_pass" class="form-control"
                            placeholder="យ៉ាងហោចណាស់ ៨ ខ្ទង់">
                        <button class="input-group-text bg-transparent" type="button"
                            onclick="togglePassword('reg_pass', 'eye_reg1')">
                            <i class="bi bi-eye-slash" id="eye_reg1"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-text"><i class="bi bi-info-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">បញ្ជាក់លេខសម្ងាត់</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <input type="password" name="password_confirmation" id="reg_confirm" class="form-control"
                            placeholder="បញ្ចូលលេខសម្ងាត់ម្ដងទៀត">
                        <button class="input-group-text bg-transparent" type="button"
                            onclick="togglePassword('reg_confirm', 'eye_reg2')">
                            <i class="bi bi-eye-slash" id="eye_reg2"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-main w-100 py-2 fw-bold">បង្កើតគណនីថ្មី <i
                        class="bi bi-arrow-right ms-2"></i></button>
            </form>
        </div>

        <div class="toggle-panel text-center" id="togglePanel">
            <div class="toggle-content px-4">
                <h4 id="toggleTitle" class="fw-bold mb-3">មិនទាន់មានគណនី?</h4>
                <p class="opacity-75 small" id="toggleDesc">ចុះឈ្មោះឥឡូវនេះ ដើម្បីគ្រប់គ្រងស្តុក
                    និងការលក់របស់អ្នកឱ្យកាន់តែងាយស្រួល។</p>
                <button class="btn btn-outline-custom" onclick="toggleForm()">
                    <span id="btnText">ប្តូរទៅចុះឈ្មោះ</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let isLogin = {{ $errors->has('name') || session('is_register') ? 'false' : 'true' }};

        function toggleForm() {
            const box = document.getElementById('authBox');
            const title = document.getElementById('toggleTitle');
            const desc = document.getElementById('toggleDesc');
            const btnText = document.getElementById('btnText');

            box.classList.toggle("active");
            isLogin = !isLogin;

            if (!isLogin) {
                title.innerText = "មានគណនីរួចហើយ?";
                desc.innerText = "សូមចូលប្រើប្រាស់គណនីរបស់អ្នក ដើម្បីបន្តការងារដែលនៅសេសសល់។";
                btnText.innerText = "ប្តូរទៅចូលប្រើ";
            } else {
                title.innerText = "មិនទាន់មានគណនី?";
                desc.innerText = "ចុះឈ្មោះឥឡូវនេះ ដើម្បីគ្រប់គ្រងស្តុក និងការលក់របស់អ្នកឱ្យកាន់តែងាយស្រួល។";
                btnText.innerText = "ប្តូរទៅចុះឈ្មោះ";
            }
        }

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>

</body>

</html>
