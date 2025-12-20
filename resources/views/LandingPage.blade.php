<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTB HR Central - UTB</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        .landing-container {
            width: 100vw;
            height: 100vh;
            position: relative;
            display: flex;
            background: white;
        }

        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
            opacity: 0.3;
        }

        .logo {
            position: absolute;
            top: 30px;
            left: 30px;
            width: 120px;
            height: auto;
            z-index: 3;
        }

        .portal-title {
            position: absolute;
            top: 50%;
            left: 30%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
        }

        .title-logo {
            width: 350px;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .title-text {
            color: #2c3e50;
            font-size: clamp(28px, 4vw, 36px);
            font-weight: 700;
            font-family: 'Georgia', 'Times New Roman', serif;
            text-align: center;
            letter-spacing: 2px;
            line-height: 1.3;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            font-style: italic;
        }

        .login-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100vh;
            background: linear-gradient(135deg, #a0aec0 0%, #cbd5e0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding-top: 15%;
            z-index: 2;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
        }

        .login-logo {
            width: 450px;
            height: auto;
            margin-bottom: 120px;
        }

        .login-title {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin-bottom: 40px;
            text-align: center;
        }

        .input-group {
            width: 80%;
            height: 50px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .input-group::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.3), transparent);
            transition: left 0.5s ease;
            z-index: 0;
            pointer-events: none;
        }

        .input-group:hover::before {
            left: 100%;
        }

        .input-group::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid transparent;
            border-radius: 8px;
            background: linear-gradient(45deg, #3498db, #2980b9, #3498db) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .input-group:hover::after {
            opacity: 1;
        }

        .input-field {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            padding: 0 20px;
            font-size: 16px;
            color: #2c3e50;
            text-align: center;
            box-sizing: border-box;
            position: relative;
            z-index: 2;
        }

        .input-field::placeholder {
            color: #95a5a6;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 80%;
            margin: 20px 0;
        }

        .login-button {
            width: 100%;
            height: 50px;
            background: #3498db;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-button::before {
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

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }

        .signup-button {
            width: 100%;
            height: 50px;
            background: transparent;
            border: 2px solid white;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .signup-button::before {
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

        .signup-button:hover::before {
            left: 100%;
        }

        .signup-button:hover {
            background: white;
            color: #2c3e50;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .logo {
                width: 80px;
                top: 20px;
                left: 20px;
            }
            
            .portal-title {
                top: 40%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            
            .title-logo {
                width: 160px;
            }
            
            .title-text {
                font-size: clamp(24px, 5vw, 32px);
            }
            
            .portal-subtitle {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: clamp(14px, 3vw, 18px);
                color: #6c757d;
            }
            
            .login-container {
                width: 100%;
                height: 100vh;
            }
            
            .login-logo {
                width: 350px;
                margin-bottom: 100px;
            }
            
            .login-title {
                font-size: 20px;
                margin-bottom: 8px;
            }
            
            .login-subtitle {
                font-size: 12px;
                margin-bottom: 30px;
            }
        }

        @media (max-height: 600px) {
            .logo {
                width: 60px;
                top: 15px;
                left: 15px;
            }
            
            .portal-title {
                top: 35%;
            }
            
            .title-logo {
                width: 120px;
            }
            
            .title-text {
                font-size: clamp(20px, 4vw, 28px);
            }
            
            .portal-subtitle {
                top: 45%;
                font-size: clamp(12px, 2.5vw, 16px);
            }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <img class="background-image" src="{{ asset('images/BestBackground.png') }}" alt="Background" />
        
               <div class="portal-title">
           <img src="{{ asset('images/HR-Central-Logo-400px.png') }}" alt="HR Central Logo" class="title-logo">
       </div>
        
               <div class="login-container">
           <img src="{{ asset('images/utblogo.png') }}" alt="UTB Logo" class="login-logo">
            
            <div class="input-group">
                <input type="email" class="input-field" id="email" placeholder="Email Address:" />
            </div>
            
            <div class="input-group">
                <input type="password" class="input-field" id="password" placeholder="Password:" />
            </div>
            
            <div class="button-group">
                <button class="login-button" onclick="handleLogin()">
                    Login
                </button>
                <a href="/signup" class="signup-button" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    Sign Up
                </a>
            </div>
        </div>
    </div>

    <script>
        function handleLogin() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                alert('Please enter both email and password');
                return;
            }
            
            // Validate email domain
            if (!email.endsWith('@utb.edu.bn')) {
                alert('Please use a valid UTB email address (@utb.edu.bn)');
                return;
            }
            
            // Create form data for login
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Send login request
            fetch('/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/dashboard';
                } else {
                    alert(data.message || 'Login failed. Please check your credentials.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during login. Please try again.');
            });
        }



        // Prevent input fields from changing size
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-field');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Prevent the input from affecting the container size
                    this.style.width = '100%';
                    this.style.height = '100%';
                });
                
                input.addEventListener('focus', function() {
                    // Ensure consistent size when focused
                    this.style.width = '100%';
                    this.style.height = '100%';
                });
            });
        });

        // Prevent scrolling and resizing
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F11' || (e.ctrlKey && e.key === 'F')) {
                e.preventDefault();
            }
        });

        // Allow context menu for developer tools
        // document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        // });

        // Prevent text selection
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>