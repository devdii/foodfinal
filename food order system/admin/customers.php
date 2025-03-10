<?php 
$conn = mysqli_connect("localhost", "root", "", "food") or die("Connection Failed: " . mysqli_connect_error());

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_query = mysqli_query($conn, "DELETE FROM `users` WHERE id = $delete_id") or die('Query failed');
    if($delete_query){
        header('location:customers.php');
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customers</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
      a:link, a:visited {
        background-color: white;
        color: black;
        border: 2px solid skyblue;
        padding: 10px 20px;
        text-decoration: none;
        margin-top: 1cm;
        margin-right: 2cm;
      }
      a:hover, a:active {
        background-color: rgb(41, 128, 185);
        color: white;
      }
    </style>
  </head>
  <body>
    <div class="href" align="right">
      <a href="./index.php">Back</a>
    </div>

    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header bg-info">
              <h4 class="text-white">Registered Customers</h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $query_run = mysqli_query($conn, "SELECT * FROM `users`");
                  if(mysqli_num_rows($query_run) > 0) {
                      while($row = mysqli_fetch_assoc($query_run)) {
                  ?>
                    <tr>
                      <td><?= $row['id']; ?></td>
                      <td><?= $row['Name']; ?></td>
                      <td><?= $row['Phone']; ?></td>
                      <td><?= $row['Email']; ?></td>
                      <td>
                        <a href="customers.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this?');">
                          <i class="fas fa-trash"></i> Delete
                        </a>
                      </td>
                    </tr>
                  <?php 
                      }
                  } else {
                  ?>
                    <tr>
                      <td colspan="5" class="text-center">No records found</td>
                    </tr>
                  <?php 
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
