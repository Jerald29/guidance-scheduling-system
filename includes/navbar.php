<!-- 
<div class="preloader flex-column justify-content-center align-items-center" style="background-color: rgba(0, 255, 255, 0); position: relative; height: 100vh;">
    <img class="animation__shake" src="../../docs/assets/img/gcs-bac.png" alt="GCS Bacoor" height="100" width="100">
    
    <div class="loading-bar-container" style="position: absolute; bottom: 10%; width: 50%;">
        <div class="loading-bar" style="width: 100%; height: 6px; background-color: #ccc; border-radius: 3px; overflow: hidden;">
            <div class="loading-bar-fill" style="width: 0%; height: 100%; background-color: #008080; animation: load 2s infinite;"></div>
        </div>
    </div>
</div>
-->

<!-- Emotion Selection Modal -->
<div id="emotionModal" class="modal fade custom-emotion-modal" tabindex="-1" role="dialog" aria-labelledby="emotionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emotionModalLabel">How are you feeling today?</h5>
            </div>
            <div class="modal-body text-center">
                <p>Select an emoji that best describes your feeling:</p>
                <div id="emoji-selection" class="d-flex justify-content-around">
                    <span class="emoji" data-emotion="happy" title="Happy">😊</span>
                    <span class="emoji" data-emotion="sad" title="Sad">😢</span>
                    <span class="emoji" data-emotion="stressed" title="Stressed">😟</span>
                    <span class="emoji" data-emotion="angry" title="Angry">😠</span>
                    <span class="emoji" data-emotion="excited" title="Excited">😁</span>
                </div>
                <p id="emotionMessage" class="mt-3 font-weight-bold"></p>
            </div>
            <div class="modal-footer">
                <!-- Initially hidden "Got It" button -->
                <button type="button" id="gotItBtn" class="btn btn-primary" style="display: none;">Got it</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Unique styles for the custom Emotion Modal */
    .custom-emotion-modal .modal-content {
        border-radius: 10px; /* Smaller border-radius */
        padding: 8px; /* Reduced padding */
        background: linear-gradient(145deg, #2e3b4e, #1a202c);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); /* Reduced shadow */
        color: #fff;
        max-width: 350px; /* Further reduced max-width for smaller modal */
        margin: 0 auto; /* Center the modal */
    }

    .custom-emotion-modal .modal-header {
        border-bottom: 1px solid #fff; /* Thinner border */
        text-align: center;
    }

    .custom-emotion-modal .modal-title {
        font-size: 1.2rem; /* Smaller font size for title */
        font-weight: 700;
        color: #00d4ff;
    }

    .custom-emotion-modal .modal-body {
        padding: 6px; /* Reduced padding */
        text-align: center;
    }

    /* Style the Emojis */
    .custom-emotion-modal .emoji {
        font-size: 1.8rem; /* Slightly smaller emoji size */
        cursor: pointer;
        transition: transform 0.3s ease, filter 0.4s ease;
        margin: 0 10px; /* Reduced margin between emojis */
    }

    /* General Animation for Emojis */
    @keyframes bounce {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
    }

    /* Apply bounce animation as default */
    .custom-emotion-modal .emoji {
        animation: bounce 2s ease infinite;
    }

    /* Hover effect */
    .custom-emotion-modal .emoji:hover {
        transform: scale(1.3);
        filter: brightness(1.3);
    }

    #gotItBtn {
        padding: 4px 10px; /* Reduced padding */
        background: #00d4ff;
        color: #1a202c;
        font-weight: 600;
        font-size: 0.9rem; /* Smaller font size */
        border-radius: 30px;
        border: none;
        cursor: pointer;
        transition: transform 0.2s ease, background 0.3s ease;
    }

    #gotItBtn:hover {
        background: #008fa3;
        transform: scale(1.05);
    }

    #emotionMessage {
        font-size: 1.2rem; /* Smaller font size */
        font-weight: 600;
        color: #ffd700;
        margin-top: 20px;
        padding: 10px 15px; /* Reduced padding */
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
        text-align: center;
        animation: fadeIn 1s ease-out;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        max-width: 80%; /* Limit the width */
        margin-left: auto;
        margin-right: auto;
    }

    /* Animation for the message appearance */
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

    /* Specific styles for different emotions */
    #emotionMessage.happy {
        background-color: rgba(255, 223, 0, 0.8); /* Bright yellow for happiness */
        color: #1a202c;
        font-style: italic;
    }

    #emotionMessage.sad {
        background-color: rgba(0, 0, 128, 0.8); /* Dark blue for sadness */
        color: #fff;
    }

    #emotionMessage.stressed {
        background-color: rgba(255, 69, 0, 0.8); /* Orange-red for stress */
        color: #fff;
    }

    #emotionMessage.angry {
        background-color: rgba(255, 0, 0, 0.8); /* Red for anger */
        color: #fff;
    }

    #emotionMessage.excited {
        background-color: rgba(0, 255, 0, 0.8); /* Green for excitement */
        color: #fff;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($_SESSION['role'] === 'Student'): ?>
        // Check if the modal has already been shown in this session
        if (!sessionStorage.getItem('emotionModalShown')) {
            // Show the modal for the first time in the session
            $('.custom-emotion-modal').modal('show');
            
            // Set a flag in sessionStorage to prevent showing it again in the same session
            sessionStorage.setItem('emotionModalShown', 'true');
        }

        // Define the motivational messages for each emoji
        const messages = {
            happy: "We're thrilled you're feeling happy! Keep spreading positivity!",
            sad: "It’s okay to feel down sometimes. Remember, you’re not alone. Reach out to us if you need to talk.",
            stressed: "Feeling stressed? Take a deep breath or speak to your counselor for guidance and support.",
            angry: "Anger can be hard to manage. Take a moment to breathe. We're here if you need assistance.",
            excited: "Excitement is a wonderful feeling! Use that energy to do something amazing today!"
        };

        // Initially hide the emotion message box
        document.getElementById('emotionMessage').style.display = 'none';

        // Add click events to each emoji
        document.querySelectorAll('.custom-emotion-modal .emoji').forEach(function (emoji) {
            emoji.addEventListener('click', function () {
                const emotion = this.getAttribute('data-emotion');
                
                // Set the message text
                document.getElementById('emotionMessage').textContent = messages[emotion];

                // Add specific class for the emotion to enhance styling
                document.getElementById('emotionMessage').className = 'emotionMessage ' + emotion;  // Add emotion-specific class
                
                // Show the emotion message box
                document.getElementById('emotionMessage').style.display = 'block';

                // Show the "Got It" button after selecting an emoji
                document.getElementById('gotItBtn').style.display = 'block';
            });
        });

        // "Got It" button closes the modal
        document.getElementById('gotItBtn').addEventListener('click', function () {
            $('.custom-emotion-modal').modal('hide');
        });
    <?php endif; ?>
});
</script>

<!-- CSS for the loading bar animation -->
<style>
@keyframes load {
    0% {
        width: 0%;
    }
    50% {
        width: 70%;
    }
    100% {
        width: 100%;
    }
}

.notification-bell-container {
    position: relative; 
}

.notification-bell {
    font-size: 14px; 
    color: #20B2AA; 
    background-color: #f8f9fa;
    border-radius: 50%; 
    padding: 5px; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.4); 
    transition: background-color 0.3s, color 0.3s; 
}

.notification-bell:hover {
    background-color: #006666;
    color: #ffffff;
}

.notification-bell:active {
    background-color: #005757; 
    color: #ffffff; 
    transform: scale(0.95); 
}

.navbar-badge {
    min-width: 10px;
    height: 11px;
    line-height: 11px; 
    text-align: center; 
    border-radius: 10px; 
    font-size: 11.5px; 
    background-color: #dc3545; 
    color: white; 
    font-weight: bold; 
    padding: 0 3px; 
}

.notification-item {
    transition: background-color 0.3s, color 0.3s, transform 0.2s; 
}

.notification-item:hover {
    transform: translateY(-2px); 
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.hover-image {
    transition: transform 0.3s ease, box-shadow 0.3s ease; 
}

.nav-link:hover .hover-image {
    transform: scale(1.1); 
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); 
}

.nav-link:active .hover-image {
    transition: transform 0.1s ease, box-shadow 0.1s ease;
    transform: scale(1); 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

</style>

<!-- Navigation bar -->
<nav class="main-header navbar navbar-expand navbar-teal navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navigation bar links -->
    <?php
    require '../../includes/conn.php'; 

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Check if session variables are set before running queries
    if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
        // Query based on the user's role
        if ($_SESSION['role'] == "Super Administrator") {
            $sa_info = mysqli_query($conn, "SELECT * FROM tbl_master_key WHERE mk_id = '{$_SESSION['id']}'");
        } elseif ($_SESSION['role'] == "Administrator") {
            $admin_info = mysqli_query($conn, "SELECT * FROM tbl_admins WHERE admin_id = '{$_SESSION['id']}'");
        } elseif ($_SESSION['role'] == "Student") {
            $student_info = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id = '{$_SESSION['id']}'");
        } elseif ($_SESSION['role'] == "Guest") {
            $guest_info = mysqli_query($conn, "SELECT * FROM tbl_guests WHERE guest_id = '{$_SESSION['id']}'");
        }

        // Fetch the query result if the query was successful
        if (isset($sa_info) || isset($admin_info) || isset($student_info) || isset($guest_info)) {
            $row = mysqli_fetch_array($sa_info ?? $admin_info ?? $student_info ?? $guest_info);

            if (!$row) {
                echo "Error fetching data: " . mysqli_error($conn);
            }
        } else {
            echo "No valid role found.";
        }

        // Fetch notifications based on user role
        if ($_SESSION['role'] == "Administrator") {
            $notifications = mysqli_query($conn, "
            SELECT 
                n.message,
                n.role,
                n.created_at,
                n.is_read,
                n.schedule_id
            FROM tbl_notifications n 
            WHERE n.admin_id = '{$_SESSION['id']}' 
            ORDER BY n.created_at DESC
        ");
        } else if ($_SESSION['role'] == "Student") {
            $student_id = $_SESSION['id']; 
            $student_notifications = mysqli_query($conn, "
                SELECT 
                    n.message,
                    n.role,
                    n.created_at,
                    n.is_read,
                    n.schedule_id
                FROM tbl_notifications n 
                WHERE n.student_id = '$student_id' 
                ORDER BY n.created_at DESC
            ");
        }
    } else {
        echo "Session variables not set.";
    }
    ?>

    <ul class="navbar-nav ml-auto">

        <?php
        if (isset($_SESSION['notification_message'])) {
            echo '<div id="notifOverlay" style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark background */
                z-index: 999;
            "></div>';

            echo '<div id="notifMessage" style="
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #f8d7da;
                color: #721c24;
                padding: 15px;
                border-radius: 5px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                z-index: 1000;
            ">' . $_SESSION['notification_message'] . '</div>';

            unset($_SESSION['notification_message']); // Remove after showing
        }
        ?>

        <script>
            setTimeout(function() {
                var notif = document.getElementById('notifMessage');
                var overlay = document.getElementById('notifOverlay');
                if (notif && overlay) {
                    notif.style.display = 'none';
                    overlay.style.display = 'none';
                }
            }, 2000); // Hides after 2 seconds (2000ms)
        </script>

        
        <!-- "Be One of Us" link -->
        <li class="nav-item">
            <a class="nav-link" href="../about/about.gcs.php">
                <i class="fas fa-handshake mr-1"></i> Be One of Us
            </a>
        </li>
        <li class="nav-item dropdown notification-bell-container">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell notification-bell"></i>
                <span class="badge badge-warning navbar-badge">
                    <?php 
                    // Count unread notifications for both admin and student
                    $admin_notifications = mysqli_query($conn, "
                        SELECT * FROM tbl_notifications 
                        WHERE admin_id = '{$_SESSION['id']}' AND role = 'Guidance Counselor' AND is_read = 0
                    ");
                    $student_notifications = mysqli_query($conn, "
                        SELECT * FROM tbl_notifications 
                        WHERE student_id = '{$_SESSION['id']}' AND role = 'Student' AND is_read = 0
                    ");
                    
                    $admin_notification_count = mysqli_num_rows($admin_notifications); 
                    $student_notification_count = mysqli_num_rows($student_notifications); 

                    // Total unread notifications count
                    $total_notification_count = $admin_notification_count + $student_notification_count;
                    echo $total_notification_count; 
                    ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" style="max-height: 538px; overflow-y: auto;">
                <span class="dropdown-item dropdown-header">
                    <?php 
                    echo $total_notification_count . ' Unread Notification' . ($total_notification_count != 1 ? 's' : ''); 
                    ?>
                </span>
                <div class="dropdown-divider" style="margin: 0;"></div>

                <?php
                // Set the timezone to the Philippines
                date_default_timezone_set('Asia/Manila');

                function getRelativeTime($createdAt) {
                    $timeDiff = time() - strtotime($createdAt); 
                    if ($timeDiff < 60) {
                        return $timeDiff . ' second' . ($timeDiff != 1 ? 's' : '') . ' ago'; 
                    } elseif ($timeDiff < 3600) {
                        return floor($timeDiff / 60) . ' minute' . (floor($timeDiff / 60) != 1 ? 's' : '') . ' ago'; 
                    } elseif ($timeDiff < 86400) {
                        return floor($timeDiff / 3600) . ' hour' . (floor($timeDiff / 3600) != 1 ? 's' : '') . ' ago'; 
                    } else {
                        return floor($timeDiff / 86400) . ' day' . (floor($timeDiff / 86400) != 1 ? 's' : '') . ' ago'; 
                    }
                }
                
                // Admin notifications
                $admin_notifications = mysqli_query($conn, "
                    SELECT * 
                    FROM tbl_notifications 
                    WHERE admin_id = '{$_SESSION['id']}' 
                    ORDER BY created_at DESC
                ");
                while ($notification = mysqli_fetch_assoc($admin_notifications)) {
                    $message = $notification['message'] ?? 'No message available.'; 
                    $isUnread = $notification['is_read'] == 0;
                    $itemStyle = $isUnread ? 'background-color: #e6f7ff; padding: 5px 10px;' : 'padding: 5px 10px;'; 
                    $dotStyle = $isUnread ? 'color: #17a2b8; font-weight: bold; line-height: 1em; margin-left: 5px;' : 'display: none;';
                    $timeAgo = getRelativeTime($notification['created_at']);
                    $schedule_id = $notification['schedule_id'];

                    echo '<a href="../schedules/view.schedule.php?schedule_id=' . $schedule_id . '" class="dropdown-item notification-item" style="' . $itemStyle . '" onclick="markAsRead(' . $notification['notif_id'] . ')">';
                    echo $message;

                    // Time ago for Admin
                    echo '<div style="text-align: center; margin-top: 2px;">';
                    echo '<i class="far fa-clock" style="color: gray; opacity: 0.7;"></i> <small style="color: gray; font-size: 0.75em; opacity: 0.7;">' . $timeAgo . '</small>';
                    echo '<span style="' . $dotStyle . '"> ●</span>';
                    echo '</div>';

                    echo '</a>'; 
                    echo '<div class="dropdown-divider" style="margin: 0;"></div>';
                }

                // Student notifications
                $student_notifications = mysqli_query($conn, "
                    SELECT * 
                    FROM tbl_notifications 
                    WHERE student_id = '{$_SESSION['id']}' AND role = 'Student'
                    ORDER BY created_at DESC
                ");
                while ($notification = mysqli_fetch_assoc($student_notifications)) {
                    $message = $notification['message'] ?? 'No message available.'; 
                    $isUnread = $notification['is_read'] == 0;
                    $itemStyle = $isUnread ? 'background-color: #e6f7ff; padding: 5px 10px;' : 'padding: 5px 10px;'; 
                    $dotStyle = $isUnread ? 'color: #17a2b8; font-weight: bold; line-height: 1em; margin-left: 5px;' : 'display: none;';
                    $timeAgo = getRelativeTime($notification['created_at']);
                    $schedule_id = $notification['schedule_id'];

                    echo '<a href="../schedules/view.my.schedule.php?schedule_id=' . $schedule_id . '" class="dropdown-item notification-item" style="' . $itemStyle . '" onclick="markAsRead(' . $notification['notif_id'] . ')">';
                    echo $message;

                    // Time ago for Student
                    echo '<div style="text-align: center; margin-top: 2px;">';
                    echo '<i class="far fa-clock" style="color: gray; opacity: 0.7;"></i> <small style="color: gray; font-size: 0.75em; opacity: 0.7;">' . $timeAgo . '</small>';
                    echo '<span style="' . $dotStyle . '"> ●</span>';
                    echo '</div>';

                    echo '</a>'; 
                    echo '<div class="dropdown-divider" style="margin: 0;"></div>';
                }
                ?>

                <a href="#" class="dropdown-item dropdown-footer hover-image" onclick="confirmClearNotifications()">Clear All Notifications</a>
                <script>
                    // Function to create and display the modal for confirmation
                    function confirmClearNotifications() {
                        const modalHTML = `
                            <div class="modal fade" id="clearNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="clearNotificationsModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="clearNotificationsModalLabel"><b>Clear Notifications</b></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to clear all notifications? This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmClearBtn">Clear Notifications</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.body.insertAdjacentHTML('beforeend', modalHTML);
                        $('#clearNotificationsModal').modal('show');
                        document.getElementById('confirmClearBtn').addEventListener('click', function() {
                            window.location.href = "../notifications/clear.notification.php";
                        });
                        $('#clearNotificationsModal').on('hidden.bs.modal', function () {
                            $(this).remove();
                        });
                    }

                    function markAsRead(notifId) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "../notifications/read.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                console.log("Notification marked as read: " + notifId);
                            }
                        };
                        xhr.send("notif_id=" + notifId);
                    }
                </script>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <?php
                // Determine the image source based on whether the user has an image
                $imageSrc = !empty($row['img']) 
                    ? 'data:image/jpeg;base64,' . base64_encode($row['img']) 
                    : '../../docs/assets/img/user2.png';
                ?>
                <img style="width: 30px; height: 30px;" src="<?php echo $imageSrc; ?>" class="user-image img-circle img-size-32 hover-image">
                <span class="badge badge-warning navbar-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <div class="dropdown-item">
                    <div class="media">
                        <?php
                        if (!empty($row['img'])) {
                        ?>
                        <img style="width: 50px; height: 50px;" src="data:image/jpeg;base64,<?php echo base64_encode($row['img']) ?>" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <?php
                        } else {
                        ?>
                        <img style="width: 50px; height: 50px;" src="../../docs/assets/img/user2.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <?php
                        }
                        ?>
                        <style>
                            .media-body {
                                max-width: 200px;
                                white-space: nowrap;
                                overflow: hidden;
                            }

                            .text-sm {
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                font-size: 16px;
                            }
                        </style>

                        <script>
                            window.onload = function() {
                                var elements = document.querySelectorAll('.media-body .dropdown-item-title, .media-body p');

                                elements.forEach(function(element) {
                                    if (element.textContent.length > 20) { // Adjust this threshold value as needed
                                        element.style.fontSize = '12px';  // Smaller font size for longer text
                                    } else {
                                        element.style.fontSize = '16px';  // Default font size for normal text
                                    }
                                });
                            };
                        </script>

                        <div class="media-body">
                            <h3 class="dropdown-item-title text-sm">
                                <b>
                                    <?php echo $_SESSION['name']; ?>
                                </b>
                            </h3>
                            <p class="text-sm"><?php echo $row['email']?></p>
                            <p class="text-sm text-muted"><i class="far fa-user mr-1"></i><?php echo $row['username']?></p>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="../user/edit.account.php" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Edit Account
                </a>
                <div class="dropdown-divider"></div>
                <a href="../login/userData/ctrl.logout.php" class="dropdown-item dropdown-footer"><b>Log Out</b></a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
