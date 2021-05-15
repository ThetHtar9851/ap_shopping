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
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) ||
       empty($_POST['quantity']) || empty($_POST['price']) ) {
      if(empty($_POST['name'])) {
        $nameError = 'Category name is required';
      }
      if(empty($_POST['description'])) {
        $descError = 'Description is required';
      }
      if(empty($_POST['category'])) {
        $catError = 'Category is required';
      }
      if(empty($_POST['quantity'])) {
        $qtyError = 'Quantity is required';
      }elseif (is_numeric($_POST['quantity']) != 1) {
         $qtyError = 'Quantity should be number';
      }
      if(empty($_POST['price'])) {
        $priceError = 'Price is required';
      }elseif (is_numeric($_POST['price']) != 1) {
        $priceError = 'Price should be number';
      }
    }else{
      if ($_FILES['image']['name'] != null) {
          $file = 'images/'.($_FILES['image']['name']);
          $imageType = pathinfo($file,PATHINFO_EXTENSION);

          if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png'){
            echo "<script>alert('image should be jpg,jpeg or png')</script>";
          }else{
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $category = $_POST['category'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
            $id = $_POST['id'];

            move_uploaded_file($_FILES['image']['tmp_name'], $file);

            $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,
                                  category_id=:category,quantity=:quantity,price=:price,image=:image WHERE id='$id'");
            $result = $stmt->execute(
              array(':name' => $name, ':description' => $desc, ':category' => $category,
                    ':quantity' => $quantity, ':price' => $price, ':image' => $image)
            );
            
            if($result) {
              echo "<script>alert('Product is updated');window.locaton.href='index.php'</script>";
            }
          }
        }else{
          $name = $_POST['name'];
          $desc = $_POST['description'];
          $category = $_POST['category'];
          $quantity = $_POST['quantity'];
          $price = $_POST['price'];
          $id = $_POST['id'];

          $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,
                                category_id=:category,quantity=:quantity,price=:price WHERE id='$id'");
          $result = $stmt->execute(
            array(':name' => $name, ':description' => $desc, ':category' => $category,
                  ':quantity' => $quantity, ':price' => $price)
          );
          
          if($result) {
            echo "<script>alert('Product is updated');window.locaton.href='index.php'</script>";
          }
        }        
      }        
    }
?>

<?php
  $stat = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
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
                <input type="hidden" name="id" value="<?php echo $result[0]['id'];?>">
                  <div class="form-group">
                    <label for="">Name</label>
                    <p style="color:red";><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                    <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <p style="color:red";><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
                    <textarea name="description" class="form-control" rows="8" cols="80">
                    <?php echo escape($result[0]['description']);?>
                    </textarea>
                  </div>
                  <div class="form-group">
                    <?php
                      $catStmt = $pdo->prepare("SELECT * FROM categories");
                      $catStmt->execute();
                      $catResult = $catStmt->fetchAll();
                    ?>
                    <label for="">Category</label>
                    <p style="color:red";><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
                    <select class="form-control" name="category">
                      <option value="">SELECT CATEGORY</option>
                      <?php foreach ($catResult as $value) { ?>
                        <?php if ($value['id'] == $result[0]['category_id']) : ?>
                          <option value="<?php echo $value['id'];?>" selected><?php echo $value['name'];?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                        <?php endif ?>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label>
                    <p style="color:red";><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
                    <input type="number" class="form-control" name="quantity" value="<?php echo escape($result[0]['quantity']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Price</label>
                    <p style="color:red";><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
                    <input type="number" class="form-control" name="price" value="<?php echo escape($result[0]['price']);?>">
                  </div>
                  <div class="form-group">
                    <label for="">Image</label>
                    <p style="color:red";><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                    <img src="images/<?php echo escape($result[0]['image']);?>" alt="" height="150" width="150"><br>
                    <input type="file" class="" name="image" value="<?php echo escape($result[0]['image']);?>">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="UPDATE">
                    <a href="index.php" type="button" class="btn btn-warning" name="">Back</a>
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