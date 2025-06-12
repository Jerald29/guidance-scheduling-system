<?php
require '../../includes/session.php';

// Check if the user is a student
if ($_SESSION['role'] != 'Student') {
    $_SESSION['error'] = "Unauthorized access.";
    header('Location: ../../login.php');
    exit();
}

// Clear any session messages
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Career Counseling | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">
    <?php include '../../includes/links.php'; ?>
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?php include '../../includes/navbar.php'; ?>
        <?php include '../../includes/sidebar.php'; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Career Counseling</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Career Counseling</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="guidance-consultation">
                <h3>Consultation with a Guidance Advocate</h3>
                
                <p><strong>Overview:</strong> The guidance advocate is available to assist you with a wide range of services to support your academic journey and personal development. Whether you're facing challenges with course selection, career planning, emotional well-being, or stress management, our advocates are here to guide you. Our services aim to empower you to make informed decisions that foster both academic success and personal growth. Each session is tailored to meet your unique needs and help you move forward with confidence.</p>

                <p><strong>Consultation Topics:</strong></p>
                <ul>
                    <li>Academic advising and course selection</li>
                    <li>Career counseling and development</li>
                    <li>Personal and emotional support</li>
                    <li>Conflict resolution and stress management</li>
                </ul>
                
                <p><strong>How to Prepare:</strong> To maximize the value of your consultation, please come prepared with relevant academic records, a list of concerns or goals, and any questions you may have. Clear communication will help your advocate tailor the session to best suit your needs. If there are any specific issues you'd like to discuss, writing them down in advance will ensure that no important topics are overlooked.</p>
                
                <button onclick="window.location.href='../schedules/add.schedule.php'" class="schedule-appointment-btn">Schedule an Appointment</button>

                <div class="more-info">
                    <button onclick="toggleMoreInfo()" class="more-info-btn">More Info</button>
                    <div id="more-info-content" class="more-info-content" style="display: none;">
                        <p><strong>Additional Services:</strong></p>
                        <ul>
                            <li>Support with scholarship and financial aid applications</li>
                            <li>Workshops on time management, study techniques, and career building</li>
                            <li>One-on-one counseling for academic or personal issues</li>
                        </ul>
                        <p>Feel free to contact our office for more details or to ask any questions regarding our services. We are here to help you navigate your academic and personal growth!</p>
                    </div>
                </div>
                
                <style>
                    .guidance-consultation {
                        background-color: #f5f5f5;
                        padding: 20px;
                        border-radius: 10px;
                        border: 1px solid #ddd;
                        margin-bottom: 20px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    }

                    .guidance-consultation h3 {
                        color: #333;
                        font-size: 26px;
                        font-weight: bold;
                        text-align: center;
                        margin-bottom: 15px;
                    }

                    .guidance-consultation p {
                        color: #555;
                        font-size: 16px;
                        text-align: justify;
                        line-height: 1.6;
                    }

                    .guidance-consultation ul {
                        color: #555;
                        font-size: 16px;
                        margin-left: 20px;
                        list-style-type: disc;
                    }

                    .schedule-appointment-btn {
                        background-color: #006400; /* Dark green color */
                        color: white;
                        border: none;
                        padding: 12px 24px;
                        font-size: 18px;
                        font-weight: bold;
                        border-radius: 5px;
                        cursor: pointer;
                        display: block;
                        margin-top: 25px;
                        width: 100%;
                        max-width: 250px;
                        margin-left: auto;
                        margin-right: auto;
                        transition: background-color 0.3s ease;
                    }

                    .schedule-appointment-btn:hover {
                        background-color: #004d00; /* Darker green for hover effect */
                    }

                    .more-info {
                        margin-top: 25px;
                    }

                    .more-info-btn {
                        background-color: #007BFF;
                        color: white;
                        padding: 12px 20px;
                        border: none;
                        font-size: 16px;
                        font-weight: bold;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }

                    .more-info-btn:hover {
                        background-color: #0056b3;
                    }

                    .more-info-content {
                        margin-top: 15px;
                        padding: 15px;
                        background-color: #e9ecef;
                        border-radius: 5px;
                        border: 1px solid #ddd;
                    }
                </style>

                <script>
                    function toggleMoreInfo() {
                        var moreInfoContent = document.getElementById("more-info-content");
                        if (moreInfoContent.style.display === "none") {
                            moreInfoContent.style.display = "block";
                        } else {
                            moreInfoContent.style.display = "none";
                        }
                    }
                </script>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <?php 
                        $programs = [
                            "Bachelor of Arts in Psychology" => [
                                "careers" => [
                                    "Counseling Psychologist" => "Provide support to individuals or groups dealing with mental health challenges and guide them toward solutions.",
                                    "Human Resources Specialist" => "Handle recruitment, training, and employee relations to improve workplace dynamics.",
                                    "Clinical Psychologist" => "Diagnose and treat mental illnesses through therapy and behavioral techniques.",
                                    "Research Analyst" => "Conduct psychological research to develop new methods in mental health care.",
                                    "Organizational Development Consultant" => "Improve organizational structures and processes to enhance productivity."
                                ],
                                "recommendation" => "Pursue internships in clinics or HR departments to gain hands-on experience. Focus on developing empathy and communication skills.",
                                "tips" => [
                                    "Join psychology organizations for networking opportunities.",
                                    "Learn basic statistical software to excel in research roles."
                                ]
                            ],
                            "Bachelor of Early Childhood Education" => [
                                "careers" => [
                                    "Preschool Teacher" => "Educate young children using creative teaching techniques to nurture their development.",
                                    "Curriculum Developer" => "Design engaging and effective educational materials for early learners.",
                                    "Child Care Center Director" => "Manage child care centers, ensuring safety and quality education.",
                                    "Educational Toy Designer" => "Create innovative toys to support children’s learning and growth.",
                                    "Family Support Specialist" => "Help families overcome challenges by connecting them with resources and support."
                                ],
                                "recommendation" => "Focus on building patience and understanding children's developmental stages. Volunteer at daycares to gain experience.",
                                "tips" => [
                                    "Take workshops on creative teaching strategies.",
                                    "Develop skills in storytelling and arts to engage children effectively."
                                ]
                            ],
                            "Bachelor of Elementary Education" => [
                                "careers" => [
                                    "Elementary School Teacher" => "Teach core subjects such as Math, Science, and English to young students.",
                                    "Special Education Teacher" => "Work with students who have physical, emotional, or learning disabilities.",
                                    "Curriculum Specialist" => "Develop educational programs and materials for schools and school districts.",
                                    "Education Consultant" => "Advise schools or educational organizations on curriculum and teaching practices.",
                                    "School Administrator" => "Manage the daily operations of a school, ensuring everything runs smoothly."
                                ],
                                "recommendation" => "Gain experience through student teaching internships. Develop classroom management skills and explore extracurricular activities.",
                                "tips" => [
                                    "Stay patient and flexible in diverse classroom settings.",
                                    "Engage in continuous learning and attend educational conferences."
                                ]
                            ],
                            "Bachelor of Physical Education" => [
                                "careers" => [
                                    "Physical Education Teacher" => "Teach physical fitness, sports, and health education to students at different levels.",
                                    "Fitness Trainer" => "Work with individuals or groups to develop fitness plans and ensure their progress.",
                                    "Sports Coach" => "Coach school, college, or professional sports teams to develop skills and strategies.",
                                    "Sports Nutritionist" => "Advise athletes on diet and nutrition to enhance performance and recovery.",
                                    "Recreational Therapist" => "Use physical activity to help people with physical or mental health conditions."
                                ],
                                "recommendation" => "Get certified in CPR and first aid. Participate in physical training workshops and stay updated with fitness trends.",
                                "tips" => [
                                    "Take courses in kinesiology and exercise science.",
                                    "Network with professionals in sports and fitness industries."
                                ]
                            ],
                            "Bachelor of Science in Business Administration - Financial Management" => [
                                "careers" => [
                                    "Financial Analyst" => "Analyze financial data to guide business decisions and investment strategies.",
                                    "Investment Banker" => "Help businesses raise capital and manage their investments.",
                                    "Accounting Manager" => "Oversee financial reporting, budgeting, and accounting operations within a company.",
                                    "Financial Planner" => "Assist individuals and businesses in planning their finances for retirement, education, etc.",
                                    "Risk Manager" => "Identify and mitigate potential financial risks for organizations."
                                ],
                                "recommendation" => "Pursue certifications like CFA or CPA. Stay updated with financial news and trends.",
                                "tips" => [
                                    "Develop strong analytical skills and attention to detail.",
                                    "Network with professionals in finance through internships and events."
                                ]
                            ],
                            "Bachelor of Science in Business Administration - Marketing Management" => [
                                "careers" => [
                                    "Marketing Manager" => "Create strategies to promote products and services to target audiences.",
                                    "Brand Manager" => "Develop and manage brand strategies to build recognition and loyalty.",
                                    "Market Research Analyst" => "Collect and analyze data to understand market trends and consumer behavior.",
                                    "Advertising Executive" => "Create and manage advertising campaigns for various media platforms.",
                                    "Public Relations Specialist" => "Manage the public image of a company or organization through media relations."
                                ],
                                "recommendation" => "Gain experience through internships or part-time marketing roles. Stay updated with digital marketing trends.",
                                "tips" => [
                                    "Learn how to use analytics tools like Google Analytics.",
                                    "Build a portfolio with successful campaigns you've been a part of."
                                ]
                            ],
                            "Bachelor of Science in Business Administration - Operations Management" => [
                                "careers" => [
                                    "Operations Manager" => "Oversee daily operations, ensuring efficiency and smooth workflow in an organization.",
                                    "Supply Chain Manager" => "Manage the movement of goods and materials from suppliers to customers.",
                                    "Logistics Coordinator" => "Plan and coordinate the storage and transportation of goods.",
                                    "Business Analyst" => "Identify business challenges and provide data-driven solutions to improve efficiency.",
                                    "Quality Control Manager" => "Ensure products or services meet the required standards of quality."
                                ],
                                "recommendation" => "Intern in operations departments to gain real-world experience. Focus on developing problem-solving skills and analytical thinking.",
                                "tips" => [
                                    "Learn how to use project management tools like Asana or Trello.",
                                    "Master supply chain management software."
                                ]
                            ],
                            "Bachelor of Science in Computer Science" => [
                                "careers" => [
                                    "Software Developer" => "Develop software applications for businesses or consumers.",
                                    "Systems Analyst" => "Design and implement IT solutions to meet organizational needs.",
                                    "Network Administrator" => "Maintain and manage computer networks in organizations.",
                                    "Data Scientist" => "Analyze large datasets to uncover trends and make predictions.",
                                    "Cybersecurity Analyst" => "Protect an organization's systems and data from cyber threats."
                                ],
                                "recommendation" => "Master coding languages like Python, Java, or C++. Participate in hackathons and collaborative tech projects.",
                                "tips" => [
                                    "Build a portfolio showcasing your coding projects.",
                                    "Stay updated with emerging technologies like AI and cloud computing."
                                ]
                            ],
                            "Bachelor of Science in Hospitality Management" => [
                                "careers" => [
                                    "Hotel Manager" => "Oversee the day-to-day operations of a hotel or resort.",
                                    "Event Planner" => "Organize and coordinate events, including conferences, weddings, and corporate events.",
                                    "Restaurant Manager" => "Manage all aspects of restaurant operations, from staff to customer service.",
                                    "Travel Consultant" => "Assist clients in planning travel itineraries and booking accommodations.",
                                    "Tourism Manager" => "Plan and manage tourism programs, including accommodations, travel logistics, and promotions."
                                ],
                                "recommendation" => "Gain hands-on experience through internships in hotels or restaurants. Learn foreign languages for international hospitality roles.",
                                "tips" => [
                                    "Work on your customer service and leadership skills.",
                                    "Stay flexible and adaptable to changing customer needs."
                                ]
                            ],
                            "Bachelor of Science in Nursing" => [
                                "careers" => [
                                    "Registered Nurse" => "Provide patient care in hospitals, clinics, or other healthcare settings.",
                                    "Nurse Educator" => "Teach nursing students and provide training to healthcare staff.",
                                    "Clinical Nurse Specialist" => "Provide advanced care in a specialized field like pediatrics or oncology.",
                                    "Nurse Practitioner" => "Perform diagnostic tests, prescribe medications, and provide patient care independently.",
                                    "Nursing Administrator" => "Manage nursing staff and operations in hospitals or clinics."
                                ],
                                "recommendation" => "Get certified in specialized fields like pediatrics or anesthesia. Gain experience through clinical rotations.",
                                "tips" => [
                                    "Develop strong communication and empathy skills.",
                                    "Stay updated with healthcare trends and nursing techniques."
                                ]
                            ],
                            "Bachelor of Science in Tourism Management" => [
                                "careers" => [
                                    "Tourism Manager" => "Manage tourism programs, including hotel stays, activities, and travel itineraries.",
                                    "Travel Consultant" => "Help clients plan travel arrangements, including flights, hotels, and tours.",
                                    "Event Coordinator" => "Plan and manage events like conventions, festivals, and exhibitions.",
                                    "Marketing Specialist" => "Promote tourism products and destinations through advertising and media campaigns.",
                                    "Sustainable Tourism Expert" => "Develop strategies for promoting eco-friendly travel and protecting natural resources."
                                ],
                                "recommendation" => "Volunteer with tourism organizations to gain hands-on experience. Stay informed on global travel trends and sustainability practices.",
                                "tips" => [
                                    "Build networking skills by attending tourism conferences and events.",
                                    "Learn to manage tourism projects effectively using management software."
                                ]
                            ],
                            "Bachelor of Secondary Education - English" => [
                                "careers" => [
                                    "English Teacher" => "Teach English literature and language to high school students.",
                                    "ESL Teacher" => "Teach English to non-native speakers in schools or abroad.",
                                    "Content Writer" => "Create written content for websites, advertisements, or publications.",
                                    "Copywriter" => "Write compelling advertising copy for products, services, or campaigns.",
                                    "Librarian" => "Manage library resources and assist students in finding relevant information."
                                ],
                                "recommendation" => "Focus on your writing and grammar skills. Gain teaching experience through internships or tutoring.",
                                "tips" => [
                                    "Develop a passion for literature and storytelling.",
                                    "Stay updated with educational technologies for teaching."
                                ]
                            ],
                            "Bachelor of Secondary Education - Filipino" => [
                                "careers" => [
                                    "Filipino Teacher" => "Teach the Filipino language, literature, and culture in schools.",
                                    "Translator" => "Translate documents, books, and other materials from Filipino to other languages and vice versa.",
                                    "Content Writer" => "Create written content in Filipino for websites, magazines, and advertisements.",
                                    "Editor" => "Edit written content to ensure clarity, grammar, and consistency in Filipino.",
                                    "Filipino Language Consultant" => "Provide expertise on the use and preservation of the Filipino language in various contexts."
                                ],
                                "recommendation" => "Immerse yourself in Filipino literature and culture. Gain practical experience by participating in language research projects.",
                                "tips" => [
                                    "Expand your vocabulary and reading comprehension in Filipino.",
                                    "Develop your communication and public speaking skills."
                                ]
                            ],
                            "Bachelor of Secondary Education - Mathematics" => [
                                "careers" => [
                                    "Mathematics Teacher" => "Teach high school mathematics, including Algebra, Calculus, and Geometry.",
                                    "Data Analyst" => "Analyze and interpret data for businesses or research purposes.",
                                    "Statistician" => "Apply mathematical principles to solve problems and conduct research in fields like healthcare or economics.",
                                    "Actuary" => "Analyze risk using mathematics and statistics to help organizations make informed financial decisions.",
                                    "Operations Research Analyst" => "Use mathematics and analytical methods to solve problems in business and logistics."
                                ],
                                "recommendation" => "Focus on building strong problem-solving and analytical skills. Consider joining math-related clubs or competitions.",
                                "tips" => [
                                    "Master mathematical modeling and computational tools.",
                                    "Stay curious and continue learning about new mathematical theories."
                                ]
                            ]
                        ];

                        foreach ($programs as $program_name => $program_details) {
                            echo "<div class='col-md-6'>";
                            echo "<div class='card hover-effect'>";
                            echo "<div class='card-header bg-olive'><h3 class='card-title'>$program_name</h3></div>";
                            echo "<div class='card-body'>";
                            
                            // Display careers with icon
                            echo "<h5><i class='fas fa-briefcase'></i> Potential Careers:</h5><ul>";
                            foreach ($program_details['careers'] as $career_name => $career_desc) {
                                echo "<li><strong>$career_name</strong>: $career_desc</li>";
                            }
                            echo "</ul>";
                            
                            // Display recommendation with icon
                            echo "<h5><i class='fas fa-thumbs-up'></i> Recommendations:</h5><p>{$program_details['recommendation']}</p>";
                            
                            // Display tips with icon
                            echo "<h5><i class='fas fa-lightbulb'></i> Tips for Success:</h5><ul>";
                            foreach ($program_details['tips'] as $tip) {
                                echo "<li>$tip</li>";
                            }
                            echo "</ul>";
                        
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }                        
                                              
                        ?>
                        <style>/* bg-olive background color */
                            /* bg-olive background color for card header */
                            .bg-olive {
                                background-color: #6B8E23; /* Olive color */
                            }

                            /* Hover effect for the entire card */
                            .hover-effect:hover {
                                box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1); /* Adding shadow on hover */
                                transform: translateY(-5px); /* Slightly lifts the card */
                                transition: all 0.3s ease; /* Smooth transition for hover effect */
                            }

                            /* Hover effect for card header to indicate interactivity */
                            .hover-effect .card-header:hover {
                                background-color: #4b6b2f; /* Darker olive for header on hover */
                                cursor: pointer;
                            }
                            .card-body h5 i {
                                margin-right: 10px; /* Adds spacing between icon and text */
                                color: #006400;
                            }

                            .card-body h5 {
                                font-weight: bold; /* Makes the section titles bold */
                            }
                        </style>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../../includes/footer.php'; ?>
    </div>
    <?php include '../../includes/script.php'; ?>
</body>
</html>
