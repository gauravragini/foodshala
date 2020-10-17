<?php
  require_once "pdo.php";
  session_start();

  if(!isset($_SESSION['rest_id'])){
      echo '<script language="javascript">';
      echo 'alert("Not logged In");';
      echo 'window.location.href = "index.php";';
      echo '</script>'; 
  }

  if(isset($_POST['name'])){
    $stmt = $pdo->prepare('INSERT INTO menu(rest_id,name,price,type,about,picname,data) Values(:r, :n, :p, :t,:a,:pic,:d)');
    $stmt->execute(array( 
        ':r' => $_SESSION['rest_id'],
        ':n' =>$_POST['name'],
        ':p' =>$_POST['price'], 
        ':t'=>$_POST['type'], 
        ':a'=>$_POST['about'],
        ':pic'=>$_FILES["pic"]["name"],
        ':d'=>file_get_contents($_FILES['pic']['tmp_name'])
    ));
    echo '<script language="javascript">';
    echo 'alert("Menu Item Sucessfully Added");';
    echo 'window.location= "restauranthome.php"';
    echo '</script>'; 
  }

  if(isset($_POST['delete'])){
    $stmt = $pdo->prepare('DELETE FROM menu where menu_id=:mid');
    $stmt->execute(array(
        ':mid' => $_POST['menu_id']));  
  }
 
  if(isset($_POST['processorder'])){
    $stmt = $pdo->prepare('UPDATE orders SET status=:s WHERE order_id=:o');
    $stmt->execute(array( 
        ':o' => $_POST['order_id'] ,
        ':s' => $_POST['status']
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

      <div class="row" id="orders" style="text-align:center">
        <h1 class="h1" style="color:white"> ALL Orders</h1><br>
        <div class="col-sm-12" style="text-align:center">
          <table class="table ">
            <thead>
              <tr>
                <th style="text-align:center; font-size:20px;">Menu Name</th>
                <th style="text-align:center; font-size:20px;">Price</th>
                <th style="text-align:center; font-size:20px;">Type</th>
                <th style="text-align:center; font-size:20px;">Order Time</th>
                <th style="text-align:center; font-size:20px;">Customer Name</th>
                <th style="text-align:center; font-size:20px;">Customer Address</th>
                <th style="text-align:center; font-size:20px;">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  $stmt = $pdo->prepare('SELECT * FROM orders where rest_id=:rid ORDER BY day DESC');
                  $stmt->execute(array( ':rid' => $_SESSION['rest_id']));
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                      $cust=$row['cust_id'];
                      $menu=$row['menu_id'];
                      $order=$row['order_id'];
                      
                      $stmt2 = $pdo->prepare('SELECT * FROM menu where menu_id=:mid');
                      $stmt2->execute(array( ':mid' => $menu));
                      $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                      $stmt3 = $pdo->prepare('SELECT * FROM customers where cust_id=:cid');
                      $stmt3->execute(array( ':cid' => $cust));
                      $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

                      echo '
                            <tr>
                              <td style="padding-top:20px;font-size:15px;text-transform: uppercase;font-weight:bolder"><a href="menu.php#'.$menu.'">'.$row2['name'].'</a></td>
                              <td style="padding-top:20px;font-size:15px;">'.$row2['price'].'</td>
                              <td style="padding-top:20px;font-size:15px;">'.$row2['type'].'</td>
                              <td style="padding-top:20px;font-size:15px;">'.$row['day'].'</td>
                              <td style="padding-top:20px;font-size:15px;">'.$row3['name'].'</td>
                              <td style="padding-top:20px;font-size:15px;width:200px">'.$row3['address'].'</td>
                              <td>';
                      if($row['status']=='completed'){
                        echo '<p style="background-color: lightgray;color: black;padding: 5px;margin-top: 15px;">'.$row['status'].'</p>';
                      }
                      else{
                        echo '
                                <form method="post">
                                  <input type="hidden" value='.$row['order_id'].' name="order_id">
                                  <input type="hidden" value="completed" name="status">
                                  <button type="submit" class="process" name="processorder">Process order</button>
                                </form>
                              </td>
                            </tr> ';}      
                    }
              ?>  
            </tbody>
          </table>
        </div>
      </div>

      <br><br><hr><br><br>

      <div class="row" id="mymenu" style="text-align:center">
          <h1 class="h1" style="color:white">My Menu</h1><br>
          <div class="col-sm-2"></div>
          <div class="col-sm-8">
            <?php
              $stmt = $pdo->prepare('SELECT * FROM menu where rest_id=:rid');
              $stmt->execute(array( ':rid' => $_SESSION['rest_id']));  
              while($row = $stmt->fetch(PDO::FETCH_ASSOC))
              {     
                $name=$row['name'];
                $type=$row['type'];
                $price=$row['price'];
                $about=$row['about'];
                $day=$row['day'];
                $data=$row['data'];
                
                echo '<div id="'.$row['menu_id'].'" >
                        <button class="collapsible">
                            <div class="row" >
                                <div class="col-xs-3 col-sm-1"><img src="./images/6.jpg" alt="Menu Image" style="width:100%;height:100%"></div>
                                <div class="col-sm-6"><h4 class="name">'.$name.'</h4></div>  
                            </div>
                        </button>
                        
                        <div class="content">
                          <div class="row menu '.$type.'" id="'.$row['menu_id'].'">
                            <div class="col-sm-3" style="text-align:center">
                                    <img src="data:image/jpeg;base64,'.base64_encode( $data ).' alt="food" style="width:100%;height:150px">
                            </div>
                            <div class="col-sm-6" style="text-align:center">
                                
                                <p class="price">$'.$price.'</p>
                                <p class="type">'.$type.'</p>
                                <p class="about">'.$about.'</p>
                            </div>
                            <div class="col-sm-3" style="padding-top:50px;">
                                <form method="post">
                                <input type="hidden" value='.$row['menu_id'].' name="menu_id">
                                <input type="submit" name="delete" class="delete" value="Delete Item">
                                </form>
                            </div>
                          </div>
                        </div>
                    </div>';
              }
            ?>
          </div>
          <div class="col-sm-2"></div>   
      </div>

      <br><br><hr><br><br>

      <div class="row" id="additem">
        <h1 class="h1" style="color:white"> Add Item</h1><br>
        <div class="col-sm-2"></div>
        <div class="col-sm-8 ">
          <div id="form" >
              <form id="write" method="post" action="restauranthome.php" enctype="multipart/form-data">
                <div class="col-sm-6">
                  <p>Item Name  <input type="text" name="name" required></p>
                  <p>Price  <input type="number" name="price" required></p>
                  <p><input type="radio" name="type" value="Veg" >&nbsp;Veg&nbsp;&nbsp;
                      <input type="radio" name="type" value="NonVeg" >&nbsp;NonVeg&nbsp;&nbsp;
                  <p>
                  <input type="file" name="pic" id="upFile" accept=".png,.gif,.jpg,.webp,jpeg"></p>
                  <p style="font-size:10px;font-style:italics;color:grey">Some image files are not supported and may lead to server error, if so try to add menu without image</p>
                  <p>About the item:
                  <textarea rows = "4" cols = "30" name = "about">
                  </textarea></p>
                </div>
                <div class="col-sm-6">
                    <img src="./images/chef.jpg" width="300" height="300">
                </div> 
                <div id="bottom" style="text-align:center">
                <input type="reset" class="process" style="background-color:lightblue;width:200px;color:black" value="Clear"> 
                <input type="submit" class="process" style="background-color:lightblue;width:200px;color:black" value="Add">
                </div>
                          
              </form>
          </div>
        </div>
        <div class="col-sm-2"></div>
      </div>

    </div>
    
    <br><br>

    <?php require_once 'footer.php'; ?>

  </body>
  
  <script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.maxHeight){
          content.style.maxHeight = null;
        } else {
          content.style.maxHeight = content.scrollHeight + "px";
        } 
      });
    }
    
  </script>

</html>