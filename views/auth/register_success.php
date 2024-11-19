<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng ký thành công - Thư viện UTT</title>
    <link rel="icon" href="./assets/images/header_ic.png" type="image/png">
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href=".\assets\css\mystyle.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FAF9F6;
        }

        .success-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
        }

        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .success-title {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .countdown {
            color: #4e73df;
            font-weight: bold;
        }

        .loading-bar {
            width: 100%;
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            margin-top: 1rem;
            overflow: hidden;
        }

        .loading-progress {
            width: 100%;
            height: 100%;
            background-color: #4e73df;
            animation: loading 3s linear;
        }

        @keyframes loading {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <i class="fas fa-check-circle success-icon"></i>
        <h1 class="success-title">Đăng ký thành công!</h1>
        <p class="success-message">Tài khoản của bạn đã được tạo thành công.</p>
        <p class="success-message">
            Đang chuyển hướng đến trang đăng nhập trong 
            <span class="countdown" id="countdown">3</span> giây...
        </p>
        <div class="loading-bar">
            <div class="loading-progress"></div>
        </div>
    </div>

    <script>
        // Countdown timer
        let timeLeft = 3;
        const countdownElement = document.getElementById('countdown');
        
        const countdownTimer = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdownTimer);
                window.location.href = 'index.php?model=auth&action=login';
            }
        }, 1000);

        // Prevent going back
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
</body>
</html>