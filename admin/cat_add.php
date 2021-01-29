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
  
  if($_POST){
    if(empty($_POST['name']) || empty($_POST['description'])) {
      if(empty($_POST['name'])) {
        $nameError = 'Category name is required';
      }
      if(empty($_POST['description'])) {
        $descError = 'Description is required';
      }
    }else {
      $name = $_POST['name'];
      $description = $_POST['description'];

      $stmt = $pdo->prepare("INSERT INTO categories(name,description) VALUES(:name,:description)");
      $result = $stmt->execute(
        array(':name'=>$name, ':description'=>$description)
      );
      if($result) {
        echo "<script>alert('Category Added');window.location.href='category.php';</script>";
      }
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
              <!-- /.card-header -->
              <div class="card-body">
                <form class="" action="cat_add.php" method="post">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red";><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <p style="color:red";><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                    <textarea name="description" class="form-control" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="category.php" type="button" class="btn btn-warning" name="">Back</a>
                  </div>
                </form>
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