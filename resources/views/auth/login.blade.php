<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EMS System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 3.8rem;
            color: #2563eb;
            margin-bottom: 12px;
        }
        .company-name {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
            display: block;
        }
        .login-header h4 {
            font-weight: 700;
            color: #1e293b;
        }
        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            margin-top: 15px;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <i class="fa-solid fa-users-gear"></i>
            <span class="company-name">EMS</span>
            <h4>Welcome Back</h4>
            <p>Sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px; font-size: 0.85rem;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0" value="{{ old('email') }}" required autofocus autocomplete="off">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted fw-semibold" style="font-size: 0.85rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 border-end-0" required autocomplete="off">
                    <button class="btn border border-start-0 bg-white text-muted" type="button" id="togglePasswordBtn" style="border-color: #cbd5e1 !important;">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>



            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In</button>
        </form>
        

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            togglePasswordBtn.addEventListener('click', function () {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle the icon
                if (type === 'text') {
                    togglePasswordIcon.classList.remove('fa-eye');
                    togglePasswordIcon.classList.add('fa-eye-slash');
                } else {
                    togglePasswordIcon.classList.remove('fa-eye-slash');
                    togglePasswordIcon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
