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
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['address'])) {
      if(empty($_POST['name'])) {
        $nameError = "Name is required";
      }
      if(empty($_POST['email'])) {
        $emailError = "Email is required";
      }
      if(empty($_POST['phone'])) {
        $phoneError = "Phone is required";
      }
      if(empty($_POST['address'])) {
        $addError = "Address is required";
      }
    }elseif(!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $passwordMaxError = "Password must be at least 4 characters";
    }else {
      $id = $_POST['id'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $phone = $_POST['phone'];
      $address = $_POST['address'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      if (empty($_POST['role'])) {
        $role = 0;
      }else {
        $role = 1;
      } 

        $stat = $pdo->prepare("SELECT * FROM users WHERE id!=:id AND email=:email");
        $stat->execute(array(':id'=>$id,':email'=>$email));
        $user = $stat->fetch(PDO::FETCH_ASSOC);

        if ($user) {
          echo "<script>alert('Email Duplicated');</script>";
        }elseif(!empty($_POST['password'])) {
          $stat = $pdo->prepare("UPDATE users SET name=:name, email=:email, phone=:phone, address=:address, password=:password, role=:role WHERE id='$id'");
          $result = $stat->execute(
           array(
            ':name'=>$name,
            ':email'=>$email,
            ':phone'=>$phone,
            ':address'=>$address,
            ':password'=>$password,
            ':role'=>$role
            )
          );
        }elseif (empty($_POST['password'])) {
          $stat = $pdo->prepare("UPDATE users SET name=:name, email=:email, role=:role WHERE id='$id'");
          $result = $stat->execute(
           array(
            ':name'=>$name,
            ':email'=>$email,
            ':role'=>$role
            )
          );
        }
        if($result){
          echo "<script>alert('User Updated');window.location.href='userList.php';</script>";
      }
    }
  }

  $stat = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stat->execute();
  $result = $stat->fetchAll();

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
                <form class="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $result[0]['id'];?>">
                    <label for="">Name</label>
                    <p style="color:red";><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">E-mail</label>
                    <p style="color:red";><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>       <input type="email" class="form-control" name="email" value="<?php echo escape($result[0]['email']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Phone</label>
                    <p style="color:red";><?php echo empty($phoneError) ? '' : '*'.$phoneError; ?></p>       
                    <input type="text" class="form-control" name="phone" value="<?php echo escape($result[0]['phone']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Address</label>
                    <p style="color:red";><?php echo empty($addError) ? '' : '*'.$addError; ?></p><input type="text" class="form-control" name="address" value="<?php echo escape($result[0]['address']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Password</label><br>
                    <span style="font-size: 15px">The user already has password!<span>
                    <p style="color:red";><?php echo empty($passwordMaxError) ? '' : '*'.$passwordMaxError; ?></p>
                    <input type="password" class="form-control" name="password" value="">
                  </div>
                  <div class="form-group">
                    <label for="">Admin</label>
                    <?php if($result[0]['role'] == 1) { ?>
                      <input type="checkbox" class="" name="role" value="1" checked>
                    <?php }else{ ?>
                      <input type="checkbox" class="" name="role" value="1" unchecked>
                    <?php } ?>  
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="UPDATE">
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