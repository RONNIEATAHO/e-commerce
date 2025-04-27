<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Now - Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('background.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            overflow: hidden;
        }

        .hero {
            text-align: center;
        }

        .welcome-text {
            font-size: 3em;
            font-weight: bold;
            opacity: 0;
            animation: fadeInText 1.5s forwards;
        }

        @keyframes fadeInText {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated-title {
            margin-top: 20px;
        }

        .animated-title span {
            font-size: 5em;
            font-weight: bold;
            display: inline-block;
            opacity: 0;
            animation: letterFade 0.6s forwards;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }

        .animated-title span:nth-child(1) { animation-delay: 2s; }
        .animated-title span:nth-child(2) { animation-delay: 2.3s; }
        .animated-title span:nth-child(3) { animation-delay: 2.6s; }
        .animated-title span:nth-child(4) { animation-delay: 2.9s; }
        .animated-title span:nth-child(5) { animation-delay: 3.2s; }
        .animated-title span:nth-child(6) { animation-delay: 3.5s; }
        .animated-title span:nth-child(7) { animation-delay: 3.8s; }
        .animated-title span:nth-child(8) { animation-delay: 4.1s; }
        .animated-title span:nth-child(9) { animation-delay: 4.4s; }

        @keyframes letterFade {
            0% {
                opacity: 0;
                transform: translateY(-30px);
                color: white;
            }
            100% {
                opacity: 1;
                transform: translateY(0);
                color: gold;
                text-shadow: 0 0 10px gold, 0 0 20px gold;
            }
        }

        .get-started-btn {
            margin-top: 50px;
            padding: 12px 28px;
            font-size: 1.2em;
            border: none;
            background-color: #f1c40f;
            color: #000;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
        }

        .get-started-btn:hover {
            background-color: #d4ac0d;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="welcome-text">Welcome to</div>

        <h1 class="animated-title">
            <span>O</span><span>R</span><span>D</span><span>E</span><span>R</span>
            <span>&nbsp;</span>
            <span>N</span><span>O</span><span>W</span>
        </h1>

        <a href="login.php" class="get-started-btn">Get Started</a>
    </div>
</body>
</html>
