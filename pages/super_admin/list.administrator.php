<?php
require '../../includes/session.php';

// Check user role
if ($_SESSION['role'] != 'Super Administrator') {
  $_SESSION['error'] = "Unauthorized access.";
  header('Location: ../../login.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administrators List | GCS Bacoor</title>

  <?php include '../../includes/links.php'; ?>

</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <?php include '../../includes/navbar.php'; ?>
    <?php include '../../includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Administrator List</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-tools">
            </div>
          </div>

          <!-- Search bar to filter administrators -->
          <div class="card-body">
            <form method="GET">
              <div class="row justify-content-center">
                <div class="col-md-4">
                  <div class="form-group">
                    <input type="text" name="search" class="form-control" placeholder="Search Administrator">
                  </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
              </div>
            </form>
          </div>

          <!-- Administrators List Table -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Fullname</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if (isset($_GET['search'])) {
                    $search = addslashes($_GET['search']);
                    
                    // Fetch matching administrators from the database
                    $admin_info = mysqli_query($conn, "SELECT *, CONCAT(admin_lname, ', ', admin_fname, ' ', admin_mname) AS fullname FROM tbl_admins 
                      WHERE admin_fname LIKE '%$search%' OR admin_mname LIKE '%$search%' OR admin_lname LIKE '%$search%' ORDER BY admin_lname");

                    // Display the search results
                    while ($row = mysqli_fetch_array($admin_info)) {
                ?>
                <tr>
                  <td class="text-center">
                    <?php
                      if (!empty(base64_encode($row['img']))) {
                        echo '<img src="data:image/jpeg;base64,'  . base64_encode($row['img']) . '" class="img zoom " alt="Admin image" style="height: 100px; width: 100px">';
                      } else {
                        echo ' <img src="../../docs/assets/img/user2.png" class="img zoom" alt="Admin image" style="height: 100px; width: 100px">';
                      } ?>
                  </td>
                  <td><?php echo $row['fullname']; ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td class="text-center">
                    <a type="button" href="edit.administrator.php?admin_id=<?php echo $row['admin_id']; ?>" class="btn btn-primary btn-sm m-1">Update</a>
                    <button class="btn btn-danger btn-sm m-1" data-toggle="modal" data-target="#modal-md<?php echo $row['admin_id']; ?>">Delete</button>
                  </td>
                </tr>
                <!-- Modal for delete confirmation -->
                <div class="modal fade" id="modal-md<?php echo $row['admin_id']; ?>">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title text-danger"><b>Delete Administrator</b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p>Are you sure you want to delete <b><?php echo strtoupper($row['fullname']); ?></b>?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="userData/ctrl.del.administrator.php?admin_id=<?php echo $row['admin_id'];?>" type="submit" name="submit" class="btn btn-danger">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                    }
                  }
                ?>
              </tbody>
              <tfoot></tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer"></div>
          <!-- /.card-footer-->
        </div>
        <!-- /.card -->

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
</body>

</html>
