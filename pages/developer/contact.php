<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Bug</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../includes/links.php">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('../../docs/assets/img/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: black; 
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding: 0;
            overflow: hidden;
        }
        .container {
            max-width: 300px; 
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6)); 
            padding: 15px; 
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        h1 {
            border-bottom: 3px solid teal;
            text-align: center;
            padding-bottom: 5px; 
            font-size: 1.5rem; 
            margin-bottom: 15px; 
            color: black; 
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5); 
        }
        ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }
        li {
            margin: 10px 0; 
            font-size: 1rem; 
            color: black; 
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3); 
        }
        a {
            color: black; 
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: #0056b3; 
            text-decoration: none; 
        }

        .btn {
            display: block;
            margin: 15px auto; 
            padding: 10px 20px; 
            background-color: teal; 
            color: #fff; 
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 1rem; 
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); 
        }

        .btn:hover {
            background-color: black; 
            color: white; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            transform: translateY(-3px); 
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                padding: 10px; 
                max-width: 90%; 
            }
            h1 {
                font-size: 1.3rem; 
            }
            .btn {
                font-size: 0.9rem;  
            }
        }                                                                   /* Para Aesthetic */
    </style>
</head>
<body>
    <div class="container">
        <h1>Developer's Contact Information</h1>
        <ul>
            <li><b>FB:</b> <a href="https://www.facebook.com/miguel.capule.m">Miguel Capule</li>
            <li><b>Email:</b> <a href="mailto:migscapule05@gmail.com">migscapule05@gmail.com</a></li> 
        </ul>

        <a href="../dashboard/index.php" class="btn">Back to Home</a>
    </div>
</body>
</html>
