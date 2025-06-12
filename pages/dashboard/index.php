<?php
require '../../includes/session.php'; 
require '../../includes/conn.php'; 

date_default_timezone_set('Asia/Manila'); // Set timezone to Philippine time

// Query to count all scheduled appointments
$sql = "SELECT COUNT(*) as total_schedules FROM tbl_schedules";
$result = $conn->query($sql);
$total_schedules = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_schedules = $row['total_schedules'];
}

// Query to count all pending schedules
$sql_pending = "SELECT COUNT(*) as total_pending FROM tbl_schedules WHERE status = 'pending'";
$result_pending = $conn->query($sql_pending);
$total_pending = 0;

if ($result_pending->num_rows > 0) {
    $row_pending = $result_pending->fetch_assoc();
    $total_pending = $row_pending['total_pending'];
}

// Query to count all completed schedules
$sql_completed = "SELECT COUNT(*) as total_completed FROM tbl_schedules WHERE status = 'completed'";
$result_completed = $conn->query($sql_completed);
$total_completed = 0;

if ($result_completed->num_rows > 0) {
    $row_completed = $result_completed->fetch_assoc();
    $total_completed = $row_completed['total_completed'];
}

// Query to count all cancelled schedules
$sql_cancelled = "SELECT COUNT(*) as total_cancelled FROM tbl_schedules WHERE status = 'cancelled'";
$result_cancelled = $conn->query($sql_cancelled);
$total_cancelled = 0;

if ($result_cancelled->num_rows > 0) {
    $row_cancelled = $result_cancelled->fetch_assoc();
    $total_cancelled = $row_cancelled['total_cancelled'];
}

// Query to count all students
$sql_students = "SELECT COUNT(*) as total_students FROM tbl_students";
$result_students = $conn->query($sql_students);
$total_students = 0;

if ($result_students->num_rows > 0) {
    $row_students = $result_students->fetch_assoc();
    $total_students = $row_students['total_students'];
}

// Query to count all confirmed schedules
$sql_confirmed = "SELECT COUNT(*) as total_confirmed FROM tbl_schedules WHERE status = 'confirmed'";
$result_confirmed = $conn->query($sql_confirmed);
$total_confirmed = 0;

if ($result_confirmed->num_rows > 0) {
    $row_confirmed = $result_confirmed->fetch_assoc();
    $total_confirmed = $row_confirmed['total_confirmed'];
}

// Query to count all administrators
$sql_admins = "SELECT COUNT(*) as total_admins FROM tbl_admins";
$result_admins = $conn->query($sql_admins);
$total_admins = 0;

if ($result_admins->num_rows > 0) {
    $row_admins = $result_admins->fetch_assoc();
    $total_admins = $row_admins['total_admins'];
}

// Query to get the total number of courses
$query = "SELECT COUNT(course_id) AS total_courses FROM tbl_courses";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_courses = $row['total_courses'];

// Query to count all evaluations
$sql = "SELECT COUNT(eval_id) as total_evaluations FROM tbl_evaluations";
$result = $conn->query($sql);
$total_evaluations = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_evaluations = $row['total_evaluations'];
}

// Query to count all sessions
$sql_sessions = "SELECT COUNT(session_id) as total_sessions FROM tbl_session_forms";
$result_sessions = $conn->query($sql_sessions);
$total_sessions = 0;

if ($result_sessions->num_rows > 0) {
    $row_sessions = $result_sessions->fetch_assoc();
    $total_sessions = $row_sessions['total_sessions'];
}
if ($_SESSION['role'] == "Super Administrator")
    // Query to count all students
    $sql_students = "SELECT COUNT(student_id) as total_students FROM tbl_students";
    $result_students = $conn->query($sql_students);
    $total_students = $result_students->num_rows > 0 ? $result_students->fetch_assoc()['total_students'] : 0;

    // Query to count all administrators
    $sql_admins = "SELECT COUNT(admin_id) as total_admins FROM tbl_admins";
    $result_admins = $conn->query($sql_admins);
    $total_admins = $result_admins->num_rows > 0 ? $result_admins->fetch_assoc()['total_admins'] : 0;

    // Query to count all master keys
    $sql_master_key = "SELECT COUNT(mk_id) as total_master_key FROM tbl_master_key";
    $result_master_key = $conn->query($sql_master_key);
    $total_master_key = $result_master_key->num_rows > 0 ? $result_master_key->fetch_assoc()['total_master_key'] : 0;

    // Calculate total number of users
    $total_users = $total_students + $total_admins + $total_master_key;

    // Query to get the announcements from the table
    $query = "SELECT announce_content FROM tbl_announce";
    $result = mysqli_query($conn, $query);

    // Check if the query is successful and there are results
    $announcements = '';
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $announcements .= '<a href="#">' . htmlspecialchars($row['announce_content']) . '</a> <span class="dot"></span> ';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | GCS Bacoor Campus</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>

    <style>
        .small-box {
            transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1), 
            box-shadow 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            border-radius: 12px; 
        }

        /* Hover effect with scaling, shadow, and rotation */
        .small-box:hover {
            transform: scale(1.08) rotate(2deg); 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25); 
        }

        /* Footer link hover animation */
        .small-box-footer {
            transition: color 0.4s ease-in-out;
            border-radius: 12px;
        }

        .small-box-footer:hover {
            color: #ffcc00; 
        }

        /* Inner text animation */
        .small-box .inner p, .small-box .inner h3 {
            transition: transform 0.3s ease-out;
        }

        /* Slight translation on hover */
        .small-box:hover .inner p {
            transform: translateY(-5px);
        }

        .small-box:hover .inner h3 {
            transform: translateY(5px);
        }
        .shadow-effect {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            color: white; 
        }
        .black-shadow-text {
            color: black; 
            text-shadow: 1px 1px 2px rgba(130, 130, 130, 0.5); 
        }    
        .welcome-title {
            font-family: 'Montserrat', sans-serif; 
            font-size: 2.5rem;                      
            font-weight: bold;                      
            text-align: center;                     
            color: #fff;                         
            position: relative;                     
            margin: 10px 0;                         
            padding: 10px;                          
            background-color: #007C7C;              
            border-radius: 12px;                    
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25); 
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            animation: fadeIn 1s ease-in-out;       
            transition: transform 0.3s ease-in-out; 
        }

        /* Hover effect with subtle scaling */
        .welcome-title:hover {
            transform: scale(1.02); 
        }

        /* Fade-in animation for smooth appearance */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px); 
            }
            to {
                opacity: 1;
                transform: translateY(0); 
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem; /* Even smaller font size on mobile devices */
                padding: 10px;   /* Adjust padding for smaller screens */
            }
        }

        #current-time {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-weight: bold;
            font-size: 0.94rem; 
            color: #ffffff; 
            background-color: #008080;
            padding: 10px 20px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); 
            display: inline-block; 
            margin-top: 5px; 
        }
        .card-body {
            position: relative;
            padding: 0;
        }

        #barChart {
            width: 100% !important; 
            height: 400px;
        }
        .mt-6 {
            display: flex; 
            justify-content: center; 
            align-items: center; 
            text-align: center; 
            flex-direction: row; 
            width: 100%; 
        }
        .logo-text {
            font-size: 0.9rem; 
            margin: 0; 
        }
        .color-tile {
            padding: 20px;
            border-radius: 10px;
            margin: 10px 0;
            text-align: center; 
            color: white; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative; 
            overflow: hidden; 
            
            /* Glass-like effect */
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.3); 
        }

        .color-icon {
            font-size: 40px; 
            margin-bottom: 10px; 
            transition: transform 0.3s ease, color 0.3s ease; 
        }

        /* Enhanced Hover Effects */
        .color-tile:hover {
            transform: translateY(-10px) scale(1.05); 
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4); 
        }

        .color-tile:hover .color-icon {
            transform: rotate(360deg) scale(1.2); /* Full rotation with scaling */
            color: black;
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), color 0.3s ease; /* Smooth and reactive transition */
        }

        .color-tile h5 {
            font-weight: bold; 
            margin: 10px 0; 
        }

        .color-tile p {
            margin: 0; 
            font-size: 14px; 
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <?php include '../../includes/navbar.php' ?>
        <?php include '../../includes/sidebar.php' ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-12 text-right">
                            <h2 class="m-0" id="current-time">
                                <?php echo date('h:i:s A'); ?>
                            </h2>
                        </div><!-- /.col -->
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center breaking-news bg-white">
                                        <div class="d-flex flex-row flex-grow-1 flex-fill justify-content-center bg-danger py-2 text-white px-1 news">
                                            <span class="d-flex align-items-center">&nbsp;<b>Announcement:</b></span>
                                        </div>

                                        <marquee class="news-scroll" behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                                            <?php
                                                // Check if there are multiple announcements
                                                if (is_array($announcements) && count($announcements) > 1) {
                                                    // Loop through the announcements and add a dot separator for multiple items
                                                    foreach ($announcements as $index => $announcement) {
                                                        echo "<span>" . $announcement . "</span>";
                                                        if ($index < count($announcements) - 1) {
                                                            // Add dot as a separator between announcements
                                                            echo "<span class='dot'></span> ";
                                                        }
                                                    }
                                                } else {
                                                    // If there's only one announcement, display it without a separator
                                                    echo "<span style='font-weight: bold;'>" . (is_array($announcements) ? $announcements[0] : $announcements) . "</span>";
                                                }
                                            ?>
                                        </marquee>

                                        <style>
                                            /* Bootstrap default styles */
                                            body {
                                                background: #eee;
                                            }

                                            .news {
                                                width: 160px;
                                            }

                                            .news-scroll a {
                                                text-decoration: none;
                                                color: black;
                                            }

                                            /* Dot separator styling */
                                            .dot {
                                                height: 6px;
                                                width: 6px;
                                                margin-left: 12px;
                                                margin-right: 12px;
                                                margin-top: 2px !important;
                                                background-color: rgb(0, 0, 0);
                                                border-radius: 50%;
                                                display: inline-block;
                                            }
                                            .breaking-news {
                                                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); 
                                            }
                                        </style>
                                    </div>
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md">
                            <div class="row">
                                <!-- ./col -->
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Calendar of Scheduled Appointments</b></p>
                                            <h3 class="shadow-effect">📅</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <a href="../schedules/student.calendar.php" class="small-box-footer">View Calendar <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Scheduled Appointments</b></p>
                                            <h3 class="shadow-effect"><?php echo $total_schedules; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Administrator") : ?>
                                            <a href="../schedules/list.schedule.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Confirmed Schedules</b></p>
                                            <h3 class="shadow-effect"><?php echo $total_confirmed; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Administrator") : ?>
                                            <a href="../schedules/list.confirmed.schedule.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <p class="black-shadow-text"><b>Pending Schedules</b></p>
                                            <h3 class="black-shadow-text"><?php echo $total_pending; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Administrator") : ?>
                                            <a href="../schedules/list.pending.schedule.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-secondary">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Students</b></p>
                                            <h3 class="shadow-effect"><?php echo $total_students; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Administrator") : ?>
                                            <a href="../student/list.students.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Guidance Advocate</b></p>
                                            <h3 class="shadow-effect"><?php echo $total_admins; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Super Administrator") : ?>
                                            <a href="../super_admin/list.administrator.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="small-box bg-olive">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Programs</b></p>
                                            <h3 class="shadow-effect"><?php echo $total_courses; ?></h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Super Administrator") : ?>
                                            <a href="../courses/list.courses.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted">More info <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <!-- Small box for Book Appointment -->
                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <p class="shadow-effect"><b>Book Appointment Now!</b></p>
                                            <h3 class="shadow-effect">+</h3>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        <?php if ($_SESSION['role'] == "Administrator") : ?>
                                            <a href="../schedules/admin.add.schedule.php" class="small-box-footer">Add Schedule <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php elseif ($_SESSION['role'] == "Student") : ?>
                                            <a href="../schedules/add.schedule.php" class="small-box-footer">Add Schedule <i class="fas fa-arrow-circle-right"></i></a>
                                        <?php else : ?>
                                            <span class="small-box-footer disabled text-muted" style="cursor: not-allowed; opacity: 0.6;">Add Schedule <i class="fas fa-ban"></i></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php if ($_SESSION['role'] == "Administrator"): ?>
                            <div class="d-flex justify-content-center flex-wrap">
                            <!-- small box for Administrator-specific links -->
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- Small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Completed Schedules</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_completed; ?></h3> <!-- Display total cancelled schedules -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <a href="../schedules/list.completed.schedule.php" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- Small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Cancelled Schedules</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_cancelled; ?></h3> <!-- Display total cancelled schedules -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <a href="../schedules/list.cancelled.schedule.php" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- Small box for Sessions -->
                                <div class="small-box bg-teal">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Student's Session Notes</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_sessions; ?></h3> <!-- Display total student sessions -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <a href="../sessions/list.session.php" class="small-box-footer">
                                        View Session Notes <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                                <!-- Small box for Evaluations -->
                                <div class="small-box bg-lightblue">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Student Evaluation</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_evaluations; ?></h3> <!-- Display total student evaluations -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <a href="../evaluation/list.eval.php" class="small-box-footer">
                                        View Evaluations <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($_SESSION['role'] == "Super Administrator"): ?>
                            <div class="d-flex justify-content-center flex-wrap">
                            <div class="col-lg-3 col-6">
                                <!-- Small box for Sessions -->
                                <div class="small-box bg-teal">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Student's Session Notes</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_sessions; ?></h3> <!-- Display total student sessions -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <!-- Small box for Total Number of Users -->
                                <div class="small-box bg-lightblue">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Total Number of Users</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_users; ?></h3> <!-- Display total number of users -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <!-- Small box for Evaluations -->
                                <div class="small-box bg-lightblue">
                                    <div class="inner">
                                        <p class="shadow-effect"><b>Student Evaluation</b></p>
                                        <h3 class="shadow-effect"><?php echo $total_evaluations; ?></h3> <!-- Display total student evaluations -->
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                                <!-- New Bar Chart -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><b>Appointments Overview</b></h3>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="barChart"></canvas> <!-- Chart Canvas -->
                                        </div>
                                    </div>
                                </div>
                                <!-- Color Meaning Trivia Section -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><b>The Essence of Color: Meanings and Interpretations</b></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Tile for Red -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #ff4d4d; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-heart"></i>
                                                        </div>
                                                        <h5>Red</h5>
                                                        <p>Represents passion, love, and energy.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for Blue -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #4d94ff; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-water"></i>
                                                        </div>
                                                        <h5>Blue</h5>
                                                        <p>Symbolizes trust, loyalty, and calmness.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for Green -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #66ff66; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-leaf"></i>
                                                        </div>
                                                        <h5>Green</h5>
                                                        <p>Represents growth, harmony, and freshness.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for Yellow -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #ffff66; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-sun"></i>
                                                        </div>
                                                        <h5>Yellow</h5>
                                                        <p>Symbolizes happiness, positivity, and energy.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for Purple -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #b366ff; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-gem"></i>
                                                        </div>
                                                        <h5>Purple</h5>
                                                        <p>Represents luxury, creativity, and wisdom.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for Orange -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #FFA500; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-fire"></i>
                                                        </div>
                                                        <h5>Orange</h5>
                                                        <p>Symbolizes enthusiasm, fascination, and encouragement.</p>
                                                    </div>
                                                </div>
                                                <!-- Tile for White -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: rgba(255, 255, 255, 0.7); color: black; border: 1px solid rgba(0, 0, 0, 0.6);">
                                                        <div class="color-icon">
                                                            <i class="fas fa-snowflake"></i>
                                                        </div>
                                                        <h5>White</h5>
                                                        <p>Symbolizes purity, innocence, and simplicity.</p>
                                                    </div>
                                                </div>

                                                <!-- Tile for Pink -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: #ff6680; color: black;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-hand-holding-heart"></i>
                                                        </div>
                                                        <h5>Pink</h5>
                                                        <p>Represents love, compassion, and nurturing.</p>
                                                    </div>
                                                </div>

                                                <!-- Tile for Black -->
                                                <div class="col-md-4">
                                                    <div class="color-tile" style="background-color: rgba(0, 0, 0, 0.7); color: white;">
                                                        <div class="color-icon">
                                                            <i class="fas fa-moon"></i>
                                                        </div>
                                                        <h5 style="color: white;">Black</h5> 
                                                        <p>Symbolizes elegance, mystery, and power.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3 mx-auto text-center shadow rounded bg-light p-4"style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <h5 class="mb-3 text-dark">Location Map</h5>
                                    <div class="embed-responsive embed-responsive-4by3 mb-3 border border-primary rounded">
                                        <iframe 
                                            class="embed-responsive-item" 
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4583.494181170126!2d120.96731067573938!3d14.425927681328012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d23e0529938f%3A0xcd8900b5875739d!2sSaint%20Francis%20of%20Assisi%20College%20-%20Bacoor%20Campus!5e1!3m2!1sen!2sph!4v1730770940521!5m2!1sen!2sph" 
                                            width="100%" 
                                            height="300" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy" 
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </div>
                                    <p class="text-muted">Find us at Saint Francis of Assisi College - Bacoor Campus.</p>
                                </div>

                                <style>
                                    .shadow {
                                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                    }
                                    .rounded {
                                        border-radius: 0.5rem;
                                    }
                                    .bg-light {
                                        background-color: #f8f9fa !important;
                                    }
                                    .text-primary {
                                        color: #007bff !important;
                                    }
                                    .border {
                                        border-width: 2px;
                                    }
                                    .border-primary {
                                        border-color: #007bff !important;
                                    }
                                </style>
                                <!-- Wisdom Words Section -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <blockquote class="blockquote">
                                                <p class="mb-0"><i>"The only way to be truly satisfied is to do what you believe is great work, and the only way to do great work is to love what you do."</i></p>
                                                <footer class="blockquote-footer">Steve Jobs</footer>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                                <!-- Logos and School's Trademark Section -->
                                <div class="container mt-1">
                                    <div class="row justify-content-center align-items-center text-center">
                                        <div class="col-auto">
                                            <img src="../../docs/assets/img/sfac-logo.png" alt="Logo 1" class="img-fluid" style="max-width: 45px;">
                                        </div>
                                        <div class="col-auto">
                                            <h6 class="logo-text" style="margin: 0;">
                                                <span class="text-danger fw-bold">
                                                    <b>Saint Francis of Assisi College</b>
                                                </span> 
                                                <b>| Office of the Guidance and Counseling Services</b>
                                            </h6>
                                        </div>
                                        <div class="col-auto">
                                            <img src="../../docs/assets/img/gcs-bac.png" alt="Logo 2" class="img-fluid" style="max-width: 47px;">
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                    <!-- Main row -->
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include '../../includes/footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php include '../../includes/script.php'; ?>
    
    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar', // Specify bar chart type
            data: {
                labels: ['Total Scheduled Appointments', 'Confirmed Schedules', 'Pending Schedules'], // Chart labels
                datasets: [{
                    label: 'Count',
                    data: [
                        <?php echo $total_schedules; ?>,
                        <?php echo $total_confirmed; ?>,
                        <?php echo $total_pending; ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1,
                    hoverBackgroundColor: [
                        'rgba(54, 162, 235, 0.8)', // Hover color for total schedules
                        'rgba(75, 192, 192, 0.8)', // Hover color for confirmed schedules
                        'rgba(255, 206, 86, 0.8)'  // Hover color for pending schedules
                    ],
                    hoverBorderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderRadius: 8, // Rounded corners on bars
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Ensure whole number y-axis values
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#fff',
                        borderWidth: 1,
                        xAlign: 'center',
                        yAlign: 'bottom',
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw || 0;
                                return [`Count: ${value}`]; 
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000, // Smooth animation duration
                    easing: 'easeOutBounce' // Easing effect
                },
                hover: {
                    onHover: function(event, elements) {
                        event.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                    },
                    mode: 'nearest',
                    intersect: true,
                    animationDuration: 400, // Animation on hover
                },
                layout: {
                    padding: {
                        top: 20, // Add padding around the chart
                        bottom: 10,
                    }
                }
            }
        });

        // Time
        setInterval(function() {
            const currentTimeElement = document.getElementById('current-time');
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            currentTimeElement.innerHTML = `${hours % 12 || 12}:${minutes}:${seconds} ${ampm}`;
        }, 1000);
    </script>
</body>

</html>

        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                            Miguel Capule                                * *-->
        <!-- * *                 Guidance Counseling System Dashboard                    * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->