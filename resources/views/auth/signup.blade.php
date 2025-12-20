<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - UTB HR Central</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signup-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 20px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 30px;
        }



        .signup-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .signup-subtitle {
            color: #6c757d;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-help {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-success {
            color: #28a745;
            font-size: 14px;
            margin-top: 5px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
            z-index: 0;
            pointer-events: none;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #6c757d;
            border: 2px solid #6c757d;
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
            z-index: 0;
            pointer-events: none;
        }

        .btn-secondary:hover::before {
            left: 100%;
        }

        .btn-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .signup-container {
                margin: 10px;
                padding: 30px 20px;
            }
            
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h1 class="signup-title">Create Account</h1>
                            <p class="signup-subtitle">UTB HR Central - UTB</p>
        </div>
        
        <form id="signupForm">
            @csrf
            
            <div class="form-group">
                <label for="first_name" class="form-label">First Name <span style="color: #dc3545;">*</span></label>
                <input type="text" id="first_name" name="first_name" required class="form-input" placeholder="Enter your first name" onblur="validateRequiredField(this, 'first_name')">
                <small id="first_nameError" class="form-error" style="display: none;">First name is required.</small>
            </div>
            <div class="form-group">
                <label for="last_name" class="form-label">Last Name <span style="color: #dc3545;">*</span></label>
                <input type="text" id="last_name" name="last_name" required class="form-input" placeholder="Enter your last name" onblur="validateRequiredField(this, 'last_name')">
                <small id="last_nameError" class="form-error" style="display: none;">Last name is required.</small>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address <span style="color: #dc3545;">*</span></label>
                <input type="email" id="email" name="email" required 
                       class="form-input"
                       placeholder="your.name@utb.edu.bn"
                       onchange="validateEmail(this.value)"
                       onblur="validateRequiredField(this, 'email')">
                <small id="emailHelp" class="form-help">Only @utb.edu.bn email addresses are allowed.</small>
                <small id="emailError" class="form-error" style="display: none;">Email address is required.</small>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="password" name="password" required 
                       class="form-input"
                       placeholder="Enter your password (minimum 6 characters)"
                       onblur="validateRequiredField(this, 'password')">
                <small class="form-help">Password must be at least 6 characters long.</small>
                <small id="passwordError" class="form-error" style="display: none;">Password is required.</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password <span style="color: #dc3545;">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" required 
                       class="form-input"
                       placeholder="Confirm your password"
                       onblur="validateRequiredField(this, 'confirm_password')">
                <small id="confirmPasswordError" class="form-error" style="display: none;">Please confirm your password.</small>
            </div>
            
            <div class="button-group">
                <button type="submit" id="signupBtn" class="btn btn-primary">
                    Create Account
                </button>
                <a href="/" class="btn btn-secondary">
                    Back to Login
                </a>
            </div>
        </form>
        
        <div id="message" class="message"></div>
    </div>

    <script>
    function validateRequiredField(input, fieldName) {
        const value = input.value.trim();
        const errorElement = document.getElementById(fieldName + 'Error');
        
        if (!value) {
            input.style.borderColor = '#dc3545';
            if (errorElement) {
                errorElement.style.display = 'block';
            }
            return false;
        } else {
            input.style.borderColor = '#28a745';
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            return true;
        }
    }

    function validateEmail(email) {
        const emailInput = document.getElementById('email');
        const emailHelp = document.getElementById('emailHelp');
        const emailError = document.getElementById('emailError');
        
        if (!email.trim()) {
            emailInput.style.borderColor = '#dc3545';
            emailHelp.style.color = '#dc3545';
            emailHelp.textContent = '✗ Email address is required';
            emailError.style.display = 'block';
            return false;
        } else if (email.endsWith('@utb.edu.bn')) {
            emailInput.style.borderColor = '#28a745';
            emailHelp.style.color = '#28a745';
            emailHelp.textContent = '✓ Valid UTB email address';
            emailError.style.display = 'none';
            return true;
        } else {
            emailInput.style.borderColor = '#dc3545';
            emailHelp.style.color = '#dc3545';
            emailHelp.textContent = '✗ Please use a valid UTB email address (@utb.edu.bn)';
            emailError.style.display = 'none';
            return false;
        }
    }

    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirm_password').value.trim();
        const messageDiv = document.getElementById('message');
        const signupBtn = document.getElementById('signupBtn');
        
        // Reset message
        messageDiv.style.display = 'none';
        
        // Comprehensive validation
        let isValid = true;
        
        // Check if all fields are filled
        if (!firstName) {
            showFieldError('first_name', 'First name is required.');
            isValid = false;
        }
        if (!lastName) {
            showFieldError('last_name', 'Last name is required.');
            isValid = false;
        }
        
        if (!email) {
            showFieldError('email', 'Email address is required.');
            isValid = false;
        }
        
        if (!password) {
            showFieldError('password', 'Password is required.');
            isValid = false;
        }
        
        if (!confirmPassword) {
            showFieldError('confirm_password', 'Please confirm your password.');
            isValid = false;
        }
        
        if (!isValid) {
            showMessage('Please fill in all required fields.', 'error');
            return;
        }
        
        // Validate email format
        if (!email.endsWith('@utb.edu.bn')) {
            showFieldError('email', 'Please use a valid UTB email address (@utb.edu.bn).');
            showMessage('Please use a valid UTB email address (@utb.edu.bn).', 'error');
            return;
        }
        
        // Validate password length
        if (password.length < 6) {
            showFieldError('password', 'Password must be at least 6 characters long.');
            showMessage('Password must be at least 6 characters long.', 'error');
            return;
        }
        
        // Validate password confirmation
        if (password !== confirmPassword) {
            showFieldError('confirm_password', 'Passwords do not match.');
            showMessage('Passwords do not match.', 'error');
            return;
        }
        
        // Disable button and show loading
        signupBtn.disabled = true;
        signupBtn.textContent = 'Creating Account...';
        
        // Create form data
        const formData = new FormData();
        formData.append('first_name', firstName);
        formData.append('last_name', lastName);
        formData.append('email', email);
        formData.append('password', password);
        formData.append('_token', '{{ csrf_token() }}');
        
        // Send signup request
        fetch('/signup', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Account created successfully! Redirecting to login...', 'success');
                setTimeout(() => {
                    window.location.href = '/';
                }, 2000);
            } else {
                showMessage(data.message || 'Failed to create account. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        })
        .finally(() => {
            signupBtn.disabled = false;
            signupBtn.textContent = 'Create Account';
        });
    });

    function showFieldError(fieldName, message) {
        const input = document.getElementById(fieldName);
        const errorElement = document.getElementById(fieldName + 'Error');
        
        if (input) {
            input.style.borderColor = '#dc3545';
        }
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    function showMessage(message, type) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = message;
        messageDiv.style.display = 'block';
        messageDiv.className = `message ${type}`;
    }
    </script>
</body>
</html> 