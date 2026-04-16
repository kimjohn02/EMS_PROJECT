<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Force Password Change - EMS</title>
    <!-- Include Bootstrap CSS for styling (assuming you use it base on other blade templates) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="m-0">Action Required</h5>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Update Your Password</h4>
                    <p class="text-muted">
                        For security reasons, you must change your default password before accessing your account dashboard.
                    </p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.force-change.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="8">
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Change Password & Continue</button>
                        </div>
                    </form>
                    
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-secondary">Logout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>