<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../includes/links.php">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('../../docs/assets/img/bac.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative; /* Allows positioning of the logo */
        }
        .logo {
            position: absolute;
            top: 20px; /* Adjust as needed */
            left: 20px; /* Adjust as needed */
            width: 100px; /* Adjust size as needed */
        }
        .container {
            max-width: 800px; 
            margin: auto; 
            padding: 20px; 
            background: rgba(255, 255, 255, 0.2); 
            border-radius: 12px; 
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4); 
            backdrop-filter: blur(10px); 
        }
        h1 {
            text-align: center; 
            padding-bottom: 10px; 
            font-size: 2.5rem;
            margin-bottom: 20px; 
            color: red; 
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        .box {
            margin: 20px 0; 
            padding: 20px; 
            border: 2px solid teal; 
            border-radius: 8px; 
            background: rgba(255, 255, 255, 0.85); 
            display: flex; 
            flex-direction: column; 
            align-items: flex-start; 
            transition: transform 0.3s, box-shadow 0.3s; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .box:hover {
            transform: scale(1.02);
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.6);
        }
        span {
            font-weight: bold; 
            font-size: 1.3rem; 
            color: red; 
            text-transform: uppercase; 
        }
        h4 {
            margin-top: 10px; 
            font-size: 1.2rem; 
            color: #333; 
            font-weight: 600; 
        }
        p {
            font-size: 1rem; 
            color: #555; 
            line-height: 1.6; 
            text-align: justify; 
            padding: 0 10px; 
        }
        .btn {
            display: block;
            margin: 30px auto; 
            padding: 12px 25px; 
            background-color: teal; 
            color: #fff; 
            text-decoration: none;
            border-radius: 5px; 
            text-align: center; 
            font-size: 1.1rem; 
            transition: background-color 0.3s, box-shadow 0.3s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
        }
        .btn:hover {
            background-color: black; 
            color: white; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
            transform: translateY(-3px); 
        }
    </style>
</head>
<body>
    <img src="../../docs/assets/img/sfac-logo.png" alt="SFAC Logo" class="logo"> <!-- Logo added here -->
    <div class="container">
        <h1>About Us</h1>

        <div class="box">
            <span>Vision</span>
            <h4>To attain our vision, we commit to:</h4>
            <p>Provide quality education. Saint Francis of Assisi College (SFAC) is a hub for progressive and holistic education, guided by the virtues of Saint Francis of Assisi that empowers learners with a solid academic foundation enriched by physical, spiritual, and cultural advancement.</p>
        </div>
        
        <div class="box">
            <span>Mission</span>
            <h4>In line with this vision, SFAC is fully committed to:</h4>
            <p>Deliver relevant, well-designed quality education from the Basic Education to the Graduate level, through its corps of highly-trained faculty; Nurture an ideal environment that hones students for academic success, molds them in cultural awareness, and prepares them for sustained participation and leadership in sports and athletics; Incorporate a values system in the curriculum that promotes love of God, family, and country; and, Integrate time-honored Filipino values in the pursuit of personal advancement.</p>
        </div>

        <div class="box">
            <span>Core Values</span>
            <h4>SFAC is guided by these core values:</h4>
            <p><b>COMMITMENT</b> – there is a commitment by each and every member of the Franciscan community to the vision set forth by the Institution.</p>
            <p><b>OPPORTUNITY</b> – there is a constant desire to seize the opportunity to become better members and leaders of the community, as well as contributors to nation-building.</p>
            <p><b>RESPECT</b> – there is an utmost respect for one’s self, peers, and co-stakeholders in the Franciscan community.</p>
            <p><b>EXCELLENCE</b> – there is a drive to always make effective use of talents and skills in order to produce outputs of the highest quality.</p>
        </div>

        <a href="../dashboard/index.php" class="btn">Back to Home</a>
    </div>
</body>
</html>
