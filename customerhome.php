<?php
  require_once "pdo.php";
  session_start();
  
  if(!isset($_SESSION['cust_id'])) {
      echo '<script language="javascript">';
      echo 'alert("Not logged In");';
      echo 'window.location.href = "index.php";';
      echo '</script>'; 
  }

  if(isset($_POST['order'])) {

    $stmt = $pdo->prepare('INSERT INTO orders(cust_id,menu_id,rest_id,status) Values(:c, :m,:r,:s)');
    $stmt->execute(array( 
        ':c' => $_SESSION['cust_id'] ,
        ':m' => $_POST['menu_id'],
        ':r' => $_POST['rest_id'],
        ':s' => "ordered"   
    )); 

    $stmt = $pdo->prepare('DELETE FROM cart where cust_id=:c and menu_id=:m');
    $stmt->execute(array( 
        ':c' => $_SESSION['cust_id'],
        ':m' => $_POST['menu_id'],
    )); 

    echo '<script language="javascript">';
    echo 'alert("Item Ordered");';
    echo '</script>';
  }

  if(isset($_POST['cancel'])) {

    $stmt = $pdo->prepare('DELETE FROM orders where order_id=:o');
    $stmt->execute(array( 
        ':o' => $_POST['order_id']
    )); 
  }
      
?>

<!DOCTYPE html>
<html>
  <?php require_once 'head.php'; ?>

  <body>
    <?php require_once 'header.php'; ?>
    
    <br><br>
   
    <div class="container">
    
      <div class="row" style="text-align:center" id="cart">
        <h1 class="h1" style="color:white"> My Cart</h1><br>
        <?php
          $stmt = $pdo->prepare('SELECT * FROM cart WHERE cust_id= :c');
          $stmt -> execute(array(':c' => $_SESSION['cust_id']));
          while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $menuid=$row['menu_id'];
            $stmt2 = $pdo->prepare('SELECT * FROM menu WHERE menu_id=:m');
            $stmt2->execute(array(':m' => $menuid));
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            echo '<div class="col-xs-8 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="foodimage">
                            <img src="data:image/jpeg;base64,'.base64_encode( $row2['data'] ).' alt="food" style="width:100%;height:200px">
                            <div class="overlay">'. $row2['name'].'</div>                        
                        </div> 
                        <p class="price">$'. $row2['price'].'</p>
                        <p class="type">'. $row2['type'].'</p>
                        <form method="post">
                          <input type="hidden" value='.$row2['menu_id'].' name="menu_id">
                          <input type="hidden" value='.$row2['rest_id'].' name="rest_id">
                          <button type="submit" name="order">Order Now</button>
                        </form>
                    </div>
                  </div>';
            }
        ?>    
      </div>

      <br><br><hr><br><br>

      <div class="row" style="text-align:center" id="ordered">
        <h1 class="h1" style="color:white">My Orders</h1><br>
        <div class="col-xs-12"  style="text-align:center;">
          <table class="table table-hover">
            <thead>
              <tr>
                <th style="">Dish Image</th>
                <th style="text-align:center; font-size:20px;" >Dish Name</th>
                <th style="text-align:center; font-size:20px;">Price</th>
                <th style="text-align:center; font-size:20px;">Type</th>
                <th style="text-align:center; font-size:20px;">Order Date</th>
                <th style="text-align:center; font-size:20px;">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $stmt = $pdo->prepare('SELECT * FROM orders WHERE cust_id=:c ORDER BY day DESC');
                $stmt->execute(array(':c' => $_SESSION['cust_id']));
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                  $menuid=$row['menu_id'];
                  $stmt2 = $pdo->prepare('SELECT * FROM menu WHERE menu_id=:m');
                  $stmt2->execute(array(':m' => $menuid));
                  $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                  echo '<tr>
                          <td><img src="data:image/jpeg;base64,'.base64_encode( $row2['data'] ).' alt="food" style="width:150px;height:100px"></td>
                          <td style="padding-top:45px;font-size:15px;text-transform: uppercase; color:blue;font-weight:bolder"><a href="menu.php#'.$menuid.'">'. $row2['name'].'</a></div>
                          <td style="padding-top:45px;font-size:15px;"><p>$'. $row2['price'].'</p></td>
                          <td style="padding-top:45px;font-size:15px;"><p>'. $row2['type'].'</p></td>
                          <td style="padding-top:45px;font-size:15px;">'.$row['day'].'</td>
                          <td>
                            <form method="post">
                                <input type="hidden" value='.$row['order_id'].' name="order_id">';
                                if($row['status']=='completed')
                                  echo '<p style="background-color: lightgreen;color: black;padding: 5px;margin-top: 30px;">Completed</p>';                      
                                else
                                  echo'<button type="submit" class="delete" name="cancel">Cancel Order</button>
                            </form>
                          </td> 
                        </tr>';
                }
              ?> 
            </tbody>
          </table>
        </div>  
      </div>

    </div>

    <br><br>

    <?php require_once 'footer.php'; ?>
    
  </body>
</html>