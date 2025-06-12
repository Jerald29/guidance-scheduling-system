<?php
require '../../includes/session.php';
require '../../includes/conn.php'; 

// Ensure the user is a student
if ($_SESSION['role'] != 'Student') {
    $_SESSION['error'] = "Unauthorized access.";
    header('location: ../../login.php');
    exit();
}

// Get student ID from the session
$student_id = $_SESSION['id'];

// Get the schedule ID from the query string
$schedule_id = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : 0;

// Fetch the appointment details
$appointment_info = $conn->prepare("SELECT s.schedule_id, st.student_fname, st.student_lname, st.img, 
        s.appointment_date, s.appointment_time, s.purpose, s.status, s.created_at, s.reschedule
        FROM tbl_schedules AS s
        LEFT JOIN tbl_students AS st ON s.student_id = st.student_id
        WHERE s.schedule_id = ? AND s.student_id = ?");
$appointment_info->bind_param("ii", $schedule_id, $student_id);
$appointment_info->execute();
$result = $appointment_info->get_result();
if ($result->num_rows == 0) {
    $_SESSION['error'] = "No appointment found.";
    header('location: list.my.schedule.php');
    exit();
}

$schedule = $result->fetch_assoc();
$reschedule_flag = $schedule['reschedule']; // Store reschedule value
// Format the date and time
$formatted_date = date("F j, Y", strtotime($schedule['appointment_date']));
$formatted_time = date("g:i A", strtotime($schedule['appointment_time']));
$formatted_created_at = date("F j, Y, g:i A", strtotime($schedule['created_at']));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Appointment | GCS Bacoor</title>
    <link rel="icon" href="../../docs/assets/img/gcs-bac.png">

    <?php include '../../includes/links.php'; ?>
    <style>
        .receipt-box {
            background-color: #fefefe;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .appointment-status {
            font-size: 1.2rem;
        }

        .table th {
            width: 30%;
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
                            <h1 class="m-0">My Appointment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">My Appointment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body">
                            <div class="receipt-box">
                                <h4 class="text-center mb-4 font-weight-bold text-uppercase" style="letter-spacing: 1px; color: #2d3436;">
                                    Appointment Details
                                </h4>
                                <table class="table table-bordered table-hover" style="border-collapse: separate; border-spacing: 0; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                                    <tbody>
                                        <tr style="background-color: #f7f7f7;">
                                            <th style="width: 35%; padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Date</th>
                                            <td style="padding: 15px; font-size: 1rem; color: #636e72;"><?php echo htmlspecialchars($formatted_date); ?></td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Time</th>
                                            <td style="padding: 15px; font-size: 1rem; color: #636e72;"><?php echo htmlspecialchars($formatted_time); ?></td>
                                        </tr>
                                        <tr style="background-color: #f7f7f7;">
                                            <th style="padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Reason for Appointment</th>
                                            <td style="padding: 15px;">
                                                <textarea class="form-control" style="font-size: 1rem; background-color: #f9f9f9; border: 1px solid #ddd;" rows="5" readonly><?php echo htmlspecialchars($schedule['purpose']); ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Status</th>
                                            <td style="padding: 15px;">
                                                <span class="badge 
                                                    <?php 
                                                        echo ($schedule['status'] == 'confirmed') ? 'bg-success' : 
                                                            (($schedule['status'] == 'cancelled') ? 'bg-danger' : 
                                                            (($schedule['status'] == 'completed') ? 'bg-info' : 'bg-warning')); 
                                                    ?>" 
                                                    style="font-size: 1rem; padding: 10px 15px; border-radius: 5px;">
                                                    <?php echo ucfirst($schedule['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr style="background-color: #f7f7f7;">
                                            <th style="padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Message</th>
                                            <td style="padding: 15px;">
                                                <?php if ($schedule['status'] == 'confirmed'): ?>
                                                    <div class="mt-3" style="color: #636e72; font-size: 1rem; line-height: 1.6;">
                                                        <p class="mb-2" style="font-size: 1.1rem; font-weight: bold;">Dear Student,</p>
                                                        <p class="mb-3" style="text-align: justify;">
                                                            We are pleased to inform you that your appointment has been successfully confirmed. 
                                                            Kindly proceed to the Guidance Office at the scheduled time and ensure you arrive a few minutes early to complete any necessary formalities. 
                                                            The Guidance Advocate will be available to assist you with your concerns and guide you through the session. 
                                                            Please bring any relevant documents or materials that may be needed for your appointment to ensure a smooth and productive meeting.
                                                        </p>
                                                        <p class="text-muted mb-3" style="font-style: italic;">Thank you for your cooperation.</p>
                                                        <p class="text-muted">
                                                            Sincerely,<br>
                                                            <strong>Ms. Joy-Shee Lyne C. Mendoza, Guidance Advocate</strong><br>
                                                            SFAC, Office of the Guidance and Counseling Services
                                                        </p>
                                                    </div>
                                                <?php elseif ($schedule['status'] == 'completed'): ?>
                                                    <div class="mt-3" style="color: #636e72; font-size: 1rem; line-height: 1.6;">
                                                        <p class="mb-2" style="font-size: 1.1rem; font-weight: bold;">Dear Student,</p>
                                                        <p class="mb-3" style="text-align: justify;">
                                                            Your appointment has been successfully completed. If you require any further assistance, feel free to schedule another appointment.
                                                        </p>
                                                        <p class="text-muted mb-3" style="font-style: italic;">Thank you for your time and cooperation.</p>
                                                        <p class="text-muted">
                                                            Sincerely,<br>
                                                            <strong>Ms. Joy-Shee Lyne C. Mendoza, Guidance Advocate</strong><br>
                                                            SFAC, Office of the Guidance and Counseling Services
                                                        </p>
                                                    </div>
                                                <?php elseif ($schedule['status'] == 'cancelled'): ?>
                                                    <div class="mt-3" style="color: #636e72; font-size: 1rem; line-height: 1.6;">
                                                        <p class="mb-2" style="font-size: 1.1rem; font-weight: bold;">Dear Student,</p>
                                                        <p class="mb-3" style="text-align: justify;">
                                                            We regret to inform you that your scheduled appointment has been cancelled.  
                                                            If you still require assistance, we encourage you to schedule a new appointment  
                                                            at a time and date that best suits your availability.  
                                                        </p>
                                                        <p class="mb-3" style="text-align: justify;">
                                                            Please check the available appointment slots and submit a new request accordingly.  
                                                            Should you need any further assistance, do not hesitate to reach out to the Guidance Office.  
                                                        </p>
                                                        <p class="text-muted">
                                                            Sincerely,<br>
                                                            <strong>Ms. Joy-Shee Lyne C. Mendoza, Guidance Advocate</strong><br>
                                                            SFAC, Office of the Guidance and Counseling Services
                                                        </p>
                                                    </div>
                                                <?php elseif ($schedule['reschedule'] == 1): ?>
                                                    <div class="mt-3" style="color: #636e72; font-size: 0.95rem;">
                                                        <p class="mb-2" style="font-size: 1.1rem; font-weight: bold;">Dear Student,</p>
                                                        <p class="mb-3" style="text-align: justify;">
                                                            Your appointment has been rescheduled. Please check your updated schedule details and make sure you are available at the new date and time.
                                                        </p>
                                                        <p class="text-muted mb-3" style="font-style: italic;">We appreciate your understanding and cooperation.</p>
                                                        <p class="text-muted">
                                                            Sincerely,<br>
                                                            <strong>Ms. Joy-Shee Lyne C. Mendoza, Guidance Advocate</strong><br>
                                                            SFAC, Office of the Guidance and Counseling Services
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="mt-3" style="color: #636e72; font-size: 0.95rem;">
                                                        <p class="mb-0" style="text-align: justify;">
                                                            Thank you for scheduling an appointment with us. At this moment, your appointment is pending confirmation. 
                                                            We kindly ask that you wait for an official confirmation from the Guidance Advocate regarding your appointment status. 
                                                            Once confirmed, you will receive a notification with the details, including the exact time and any instructions to prepare for your session. 
                                                            We appreciate your patience and understanding as we process your request, and we are looking forward to assisting you.
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="padding: 15px; font-size: 1rem; text-transform: uppercase; color: #2d3436;">Created At</th>
                                            <td style="padding: 15px; font-size: 1rem; color: #636e72;"><?php echo htmlspecialchars($formatted_created_at); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <?php if ($schedule['status'] == 'cancelled' || $schedule['status'] == 'completed'): ?>
                                <?php elseif ($schedule['reschedule'] == 1 && $schedule['status'] != 'confirmed'): ?>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-decline<?php echo $schedule['schedule_id']; ?>">
                                            <strong>Decline</strong>
                                        </button>
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-confirm<?php echo $schedule['schedule_id']; ?>">
                                            <strong>Accept</strong>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <!-- Accept Modal -->
                                <div class="modal fade" id="modal-confirm<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content" style="border: 2px solid green;">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title"><b>Confirm Reschedule</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Do you want to proceed with this rescheduled appointment?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="confirm.reschedule.php" method="POST">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">
                                                    <button type="submit" name="confirm_reschedule" class="btn btn-success">Confirm</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Decline Modal -->
                                <div class="modal fade" id="modal-decline<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content" style="border: 2px solid #ffc107;">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title"><b>Decline Reschedule</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to decline this reschedule request?</p>
                                                <p>This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <form action="decline.reschedule.php" method="POST">
                                                    <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">
                                                    <button type="submit" name="decline_reschedule" class="btn btn-warning">Decline</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="modal-delete-<?php echo $schedule['schedule_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content" style="border: 2px solid #dc3545;">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title"><b>Delete Schedule</b></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete the schedule for <b><?php echo htmlspecialchars(date('F j, Y', strtotime($schedule['appointment_date']))); ?></b> at <b><?php echo htmlspecialchars(date('g:i A', strtotime($schedule['appointment_time']))); ?></b>?</p>
                                                <p>This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <a href="userData/ctrl.del.list.my.schedule.php?schedule_id=<?php echo $schedule['schedule_id']; ?>" class="btn btn-danger">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="text-align: center; margin-top: 20px;">
                                    <?php if ($schedule['status'] == 'confirmed' || $schedule['status'] == 'completed'): ?>
                                        <a href="appointment.slip.php?schedule_id=<?php echo $schedule_id; ?>" class="btn btn-info">View Appointment Slip</a>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include '../../includes/footer.php'; ?>
    </div>

    <?php include '../../includes/script.php'; ?>
</body>

</html>

<?php
$appointment_info->close();
$conn->close(); 
?>
