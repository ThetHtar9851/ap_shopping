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
                <h3 class="card-title">Order Detail</h3>
              </div>
              <?php
                if (!empty($_GET['pageno'])) {
                  $pageno = $_GET['pageno'];
                }else{
                  $pageno = 1;
                }

                $numOfrecs = 5;
                $offset = ($pageno - 1) * $numOfrecs;

                $stmt = $pdo->prepare("SELECT * FROM sale_order_detail ORDER BY id DESC      LIMIT $offset,$numOfrecs");
                $stmt->execute();
                $result = $stmt->fetchAll();
                
              ?>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <a href="order_list.php" type="button" class="btn btn-default">Back</a>
                </div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $i=1;
                      if ($result) {
                        foreach ($result as $value) {
                          $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                          $pStmt->execute();
                          $pResult = $pStmt->fetchAll();
                    ?>
                    <tr>
                      <td><?php echo $i + $offset; ?></td>
                      <td><?php echo escape($pResult[0]['name']);?></td>
                      <td><?php echo escape($value['quantity']);?></td>
                      <td><?php echo escape(date('Y-m-d',strtotime($value['order_date'])));?></td>         
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
