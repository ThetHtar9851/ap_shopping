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
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4) {
      if(empty($_POST['name'])) {
        $nameError = "Name is required";
      }
      if(empty($_POST['email'])) {
        $emailError = "Email is required";
      }
      if(empty($_POST['password'])) {
        $passwordError = "Password is required";
      }
      if(strlen($_POST['password']) < 4) {
        $passwordMaxError = "Password must be at least 4 characters";
      }
    }else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      if (empty($_POST['role'])) {
        $role = 0;
      }else {
        $role = 1;
      }    
      $stat = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stat->bindValue(':email',$email);
      $stat->execute();
      $user = $stat->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        echo "<script>alert('Email Duplicated');window.location.href='userAdd.php';</script>";
      }else {
        $stat = $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,:role)");
        $result = $stat->execute(
          array(
            ':name'=>$name,
            ':email'=>$email,
            ':password'=>$password,
            ':role'=>$role
          )
        );
        if($result){
          echo "<script>alert('User Added');window.location.href='userList.php';</script>";
        }
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
                <form class="" action="userAdd.php" method="post">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red";><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="">
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <p style="color:red";><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                    <input type="email" class="form-control" name="email" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Password</label>
                    <p style="color:red";>
                      <?php 
                       if(!empty($passwordError)){
                        echo '*'.$passwordError;
                       }
                       elseif (!empty($passwordMaxError)) {
                        echo '*'.$passwordMaxError;
                       }
                      ?>
                    </p>
                    <input type="password" class="form-control" name="password" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Admin</label>
                    <input type="checkbox" class="" name="role" value="1">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                    <a href="userList.php" type="button" class="btn btn-warning" name="">Back</a>
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