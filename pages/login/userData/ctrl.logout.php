<?php
session_start();
session_destroy(); // End the PHP session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Logging Out...</title>
    <style>
        /* General body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #004d40; 
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
            animation: fadeIn 1s ease;
            font-size: 0.9rem;
        }

        /* Animation for fading in */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Spinner animation for loading */
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 8px solid #00d4ff;
            border-radius: 50%;
            width: 30px; 
            height: 30px; 
            animation: spin 1.5s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Styled logout message */
        h2 {
            font-size: 1.2rem;
            margin-top: 10px; 
            color: #ffffff;
            animation: fadeIn 1s ease-out;
        }

        /* Smooth fade effect for completion message */
        #completionMessage {
            display: none;
            font-size: 0.9rem; /* Slightly smaller font size */
            color: #00d4ff;
            margin-top: 10px; /* Reduced margin */
            font-weight: bold;
            animation: fadeIn 1s ease-out;
        }
    </style>

    <script>
        // Clear the session storage for emotion modal visibility
        sessionStorage.removeItem('emotionModalShown');
        
        // Final message and redirection after a short delay
        setTimeout(function() {
            document.getElementById('completionMessage').style.display = 'block';
            setTimeout(function() {
                window.location.href = "../login.php"; // Redirect to login page
            }, 500); // Reduced delay for redirection to 500ms
        }, 1000); // Reduced delay for showing completion message to 1 second
    </script>
</head>
<body>
    <!-- Background Animation (Soft Green and Teal Gradient) -->
    <div class="background-animation"></div>

    <!-- Loading spinner -->
    <div class="spinner"></div>

    <!-- Logout message -->
    <h2>Logging you out, please wait...</h2>

    <!-- Completion message after logout -->
    <div id="completionMessage">You have been successfully logged out. Redirecting to login...</div>
</body>
</html>
