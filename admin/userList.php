<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if($_SESSION['role'] != 1) {
    header('Location: login.php');  
  }

  if(!(empty($_POST['search']))){
    setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
  }else {
    if (empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search', null, -1, '/');
    }
  }
  
?>

<?php include 'header.php';?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User Listings</h3>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }

                $numOfrecs = 5;
                $offset = ($pageno - 1) * $numOfrecs;

                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();

                  $total_pages = ceil(count($rawResult) / $numOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }else{
                  $searchKey = !(empty($_POST['search'])) ? $_POST['search'] : $_COOKIE['search'];
                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult = $stmt->fetchAll();

                  $total_pages = ceil(count($rawResult) / $numOfrecs);

                  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }

              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="userAdd.php" type="button" class="btn btn-success">New User</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>E-mail</th>
                      <th>Role</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $i=1;
                      if ($result) {
                        foreach ($result as $value) {
                    ?>
                    <tr>
                      <td><?php echo $i + $offset; ?></td>
                      <td><?php echo escape($value['name']);?></td>
                      <td><?php echo escape($value['email']);?></td>
                      <td>
                        <?php 
                          if($value['role'] == 1) {
                            echo 'admin';
                          }else {
                           echo 'user';
                          }
                        ?>
                      </td>            
                      <td>
                        <div class="btn-group">
                          <div class="container">
                            <a href="userEdit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container"> 
                            <a href="userDelete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure want to delete?')">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php
                      $i++;
                        }
                      }
                    ?>
                    
                  </tbody>
                </table><br>
                <nav aria-label="Page navigation example" style="float:right">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1) {echo '#';}else {echo "?pageno=".($pageno-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages) {echo '#';}else{echo "?pageno=".($pageno+1);}?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

 <?php include 'footer.html'; ?>
