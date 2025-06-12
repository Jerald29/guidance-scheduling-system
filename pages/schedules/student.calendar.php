<?php
require '../../includes/session.php'; 
require '../../includes/conn.php';

// Get selected year and month from POST request, default to current year and month
$selectedYear = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
$selectedMonth = isset($_POST['month']) ? intval($_POST['month']) : date('n');

$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Appointment Calendar | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
    <style>
        /* Calendar styles */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        .day {
            border: 1px solid #dee2e6;
            padding: 10px;
            background-color: #f8f9fa;
            height: 150px;
            position: relative;
            overflow-y: auto;
            border-radius: 4px; 
            transition: background-color 0.3s, transform 0.3s;
        }
        .day:hover {
            background-color: #e2e6ea;
            transform: scale(1.05);
            cursor: pointer;
        }
        .day h5 {
            margin: 0;
            font-size: 1.2em;
        }
        .appointment {
            padding: 5px;
            margin-top: 5px;
            border-radius: 3px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .appointment:hover {
            transform: scale(1.05);
            background-color: #343a40;
            color: #fff;
        }
        .confirmed {
            background-color: #28a745; 
            color: #fff;
        }
        .completed {
            background-color: #17a2b8;
            color: #fff;
        }
        .pending {
            background-color: #ffc107; 
            color: #000;
        }
        .cancelled {
            background-color: #dc3545;
            color: #fff;
        }
        .empty {
            background-color: #e9ecef;
        }
        .my-schedule-confirmed {
            background-color: #28a745;
            color: #fff;
        }
        .my-schedule-pending {
            background-color: #ffc107;
            color: #000;
        }
        .my-schedule-completed {
            background-color: #17a2b8;
            color: #fff;
        }
        .my-schedule-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .calendar {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 576px) {
            .calendar {
                grid-template-columns: repeat(2, 1fr);
            }
            .day {
                height: auto;
            }
        }
    </style>
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
                            <h1 class="m-0">Scheduled Appointments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Scheduled Appointments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="card">
                    <div class="card-body" style="padding: 50px; font-size: 0.9rem;">
                        <div class="d-flex justify-content">
                            <form method="post" action="" style="width: 300px;">
                                <div class="form-group">
                                    <label for="year">Select Year:</label>
                                    <select name="year" id="year" class="form-control select2">
                                        <?php
                                        for ($i = 2020; $i <= date('Y') + 1; $i++) {
                                            echo '<option value="' . $i . '"' . ($i == $selectedYear ? ' selected' : '') . '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="month">Select Month:</label>
                                    <select name="month" id="month" class="form-control select2">
                                        <?php
                                        for ($m = 1; $m <= 12; $m++) {
                                            echo '<option value="' . $m . '"' . ($m == $selectedMonth ? ' selected' : '') . '>' . DateTime::createFromFormat('!m', $m)->format('F') . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">View Schedule</button>
                            </form>
                        </div>

                        <div class="legend text-left mb-3 mt-5">
                            <h5><b>Legend:</b></h5>
                            <div>
                                <span class="badge bg-warning text-dark">Pending Appointments</span>
                                <span class="badge bg-success">Confirmed Appointments</span>
                                <span class="badge bg-info">Completed Appointments</span>
                                <span class="badge bg-danger">Cancelled Appointments</span>
                            </div>
                        </div>

                        <h2 class="text-center mt-1">
                            <strong>
                                <?php
                                $monthName = DateTime::createFromFormat('!m', $selectedMonth)->format('F');
                                echo $monthName . ' ' . $selectedYear;
                                ?>
                            </strong>
                        </h2>

                        <div class="calendar">
                            <?php
                                // Days of the week header
                                $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                foreach ($daysOfWeek as $day) {
                                    echo '<div class="text-center font-weight-bold">' . $day . '</div>';
                                }

                                $sql = "SELECT s.student_lname, sc.schedule_id, sc.appointment_date, sc.appointment_time, sc.status, sc.student_id 
                                    FROM tbl_schedules sc 
                                    JOIN tbl_students s ON sc.student_id = s.student_id 
                                    WHERE sc.status IN ('confirmed', 'pending', 'completed', 'cancelled') 
                                    AND YEAR(sc.appointment_date) = $selectedYear 
                                    AND MONTH(sc.appointment_date) = $selectedMonth
                                    ORDER BY sc.appointment_date ASC, sc.appointment_time ASC";                                             

                                $result = $conn->query($sql);

                                $appointments = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $appointments[$row['appointment_date']][] = $row;
                                        usort($appointments[$row['appointment_date']], function ($a, $b) {
                                            return strtotime($a['appointment_time']) - strtotime($b['appointment_time']);
                                        });
                                    }
                                }

                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
                                $firstDayOfMonth = new DateTime("$selectedYear-$selectedMonth-01");

                                // Blank spaces for the first week
                                $startDay = $firstDayOfMonth->format('w');
                                for ($i = 0; $i < $startDay; $i++) {
                                    echo '<div class="day empty"></div>';
                                }

                                // Loop through all days of the month
                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                    $currentDay = new DateTime("$selectedYear-$selectedMonth-$day");
                                    $dateStr = $currentDay->format('Y-m-d');
                                    echo '<div class="day' . (!isset($appointments[$dateStr]) ? ' empty' : '') . '">';
                                    echo '<h5>' . $currentDay->format('d M') . '</h5>';

                                    // Check if there are any appointments for the current day
                                    if (isset($appointments[$dateStr])) {
                                        usort($appointments[$dateStr], function ($a, $b) {
                                            return strtotime($a['appointment_time']) - strtotime($b['appointment_time']);
                                        });
                                        foreach ($appointments[$dateStr] as $appointment) {                                        
                                            $timeFormatted = date('h:i A', strtotime($appointment['appointment_time']));
                                        
                                            // Assign the correct status class
                                            $statusClass = ($appointment['status'] === 'confirmed') ? 'confirmed' :
                                                           (($appointment['status'] === 'pending') ? 'pending' :
                                                           (($appointment['status'] === 'completed') ? 'completed' : 'cancelled'));
                                        
                                            if ($_SESSION['role'] === 'Administrator') {
                                                // Administrators see all student names and can click on any appointment
                                                echo '<a href="../schedules/view.schedule.php?schedule_id=' . $appointment['schedule_id'] . '">';
                                                echo '<div class="appointment ' . $statusClass . '">' . htmlspecialchars($timeFormatted) . 
                                                     ' - Student: ' . htmlspecialchars($appointment['student_lname']) . '</div>';
                                                echo '</a>';
                                            } elseif ($appointment['student_id'] == $student_id) {
                                                // Students can only see their own appointments with their name
                                                $statusClass = ($appointment['status'] === 'confirmed') ? 'my-schedule-confirmed' :
                                                               (($appointment['status'] === 'pending') ? 'my-schedule-pending' :
                                                               (($appointment['status'] === 'completed') ? 'my-schedule-completed' : 'my-schedule-cancelled'));
                                        
                                                echo '<a href="../schedules/view.my.schedule.php?schedule_id=' . $appointment['schedule_id'] . '">';
                                                echo '<div class="appointment ' . $statusClass . '">' . htmlspecialchars($timeFormatted) . ' (My Schedule)</div>';
                                                echo '</a>';
                                            } else {
                                                // Other students see only the time and anonymized status
                                                echo '<div class="appointment ' . $statusClass . '">' . htmlspecialchars($timeFormatted) . ' - Booked</div>';
                                            }
                                        }                                                                              
                                    }

                                    echo '</div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/footer.php'; ?>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <?php include '../../includes/script.php'; ?>

</body>

</html>

<?php
$conn->close(); 
?>
