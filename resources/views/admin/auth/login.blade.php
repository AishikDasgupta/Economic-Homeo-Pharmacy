<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Economic Homeo Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo img {
            max-width: 150px;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="logo">
                <img src="/images/logo.png" alt="Economic Homeo Pharmacy Logo">
                <h4 class="mt-2">Admin Panel</h4>
            </div>
            
            <div class="alert alert-danger" id="error-message" role="alert"></div>
            
            <form id="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="login-button">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="login-spinner"></span>
                    <span id="login-text">Login</span>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const errorMessage = document.getElementById('error-message');
            const loginButton = document.getElementById('login-button');
            const loginSpinner = document.getElementById('login-spinner');
            const loginText = document.getElementById('login-text');
            
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                loginButton.disabled = true;
                loginSpinner.classList.remove('d-none');
                loginText.textContent = 'Logging in...';
                errorMessage.style.display = 'none';
                
                const formData = {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value
                };
                
                fetch('/api/admin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        // Store token and user data
                        localStorage.setItem('admin_token', data.token);
                        localStorage.setItem('admin_user', JSON.stringify(data.user));
                        
                        // Redirect to admin dashboard
                        window.location.href = '/admin/dashboard';
                    } else {
                        throw new Error(data.message || 'Login failed');
                    }
                })
                .catch(error => {
                    // Reset button state
                    loginButton.disabled = false;
                    loginSpinner.classList.add('d-none');
                    loginText.textContent = 'Login';
                    
                    // Show error message
                    errorMessage.textContent = error.message || 'An error occurred during login. Please try again.';
                    errorMessage.style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>