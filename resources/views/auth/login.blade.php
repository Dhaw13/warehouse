<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warehouse Logistic</title>

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ✅ Font -->
    <link href="https://fonts.bunny.net/css?family=Poppins:400,500,600,700" rel="stylesheet">

</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100" style="font-family: 'Poppins', sans-serif;">

    <div class="container">
        <div class="row shadow-lg rounded overflow-hidden mx-auto" style="max-width: 900px; min-height: 500px;">
            
            <!-- ✅ Left Side -->
            <div class="col-md-6 bg-white d-flex flex-column align-items-center justify-content-center text-center p-4">
                <h2 class="fw-bold text-uppercase mb-3">
                    Welcome Back, <span class="text-danger">User</span>
                </h2>
                <p class="text-muted mb-4">Log in now to continue</p>
                <img src="{{ asset('storage/src/bgudang.png') }}" alt="Warehouse Illustration" class="img-fluid" style="max-width: 300px;">
            </div>

            <!-- ✅ Right Side -->
            <div class="col-md-6 d-flex align-items-center justify-content-center text-white" style="background-color: #7e0c19ff;">
                <div class="w-75">
                    <h3 class="text-center fw-bold mb-4">LOGIN</h3>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-light fw-semibold text-danger">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
