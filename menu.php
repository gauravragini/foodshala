<?php
require_once "pdo.php";
session_start();

    if(isset($_POST['add']))
    {
        if(isset($_SESSION['rest_id'])){
            echo '<script language="javascript">';
            echo 'alert("Please Login as Customer");';
            echo 'window.location.href = "index.php";';
            echo '</script>'; 
        }    
        if(!isset($_SESSION['cust_id'])){
            echo '<script language="javascript">';
            echo 'alert("Not logged in");';
            echo 'window.location.href = "index.php";';
            echo '</script>'; 
        }
         else{
        $stmt = $pdo->prepare('SELECT cust_id,menu_id FROM cart WHERE cust_id = :c AND menu_id = :m');
        $stmt->execute(array( 
            ':c' => $_SESSION['cust_id'] ,
            ':m' => $_POST['menu_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        if ( $row != false ) {
            echo '<script language="javascript">';
            echo 'alert("Already added in cart");';
            echo 'window.location.href = "menu.php";';
            echo '</script>';
            return;
        }

        $stmt = $pdo->prepare('INSERT INTO cart(cust_id,menu_id) Values(:c, :m)');
        $stmt->execute(array( 
            ':c' => $_SESSION['cust_id'] ,
            ':m' => $_POST['menu_id']
            
        )); 
            echo '<script language="javascript">';
            echo 'window.location.href = "menu.php";';
            echo 'alert("added to cart");';
            echo '</script>';

    }
        }

    
?> 

<!DOCTYPE html>
<html>
    <?php require_once 'header.php'; ?>
    
      
    <body style="">
    <?php require_once 'head.php'; ?>
            <div class="container-fluid">

                <div class="row" >
                    <div class="col-sm-12"id="top" style="text-align:center">
                        <br><br>
                        <img  src="./images/menu.jpg" height="200" width="200">
                    </div>  
                </div>

                <div class="row">
                    <div class="col-sm-2" id="side" style="text-align:center;height:1000vh">
                        <h3>Preferences</h3>            
                        <button class="btn" id="Veg" onclick="filter(this)">Veg</button>
                        <button class="btn" id="NonVeg" onclick="filter(this)">Non Veg</button>
                        <button class="btn" id="Both" onclick="filter(this)">All</button>
                        <img src="./images/bg.jpg" height="160" width="160" style="margin-top:40px;">
                        <a href="#top" style="position:fixed; bottom:0;left:0; font-size:20px;margin-left:80px;">Go To Top</a>
                    </div>
                    <div class="col-sm-8" style="padding-left:40px;padding-right:40px;">
                            
                            <?php $stmt = $pdo->prepare('SELECT * FROM menu ');
                            $stmt->execute(array());
                        
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                                {
                                    
                                    $name=$row['name'];
                                    $type=$row['type'];
                                    $price=$row['price'];
                                    $about=$row['about'];
                                    $day=$row['day'];
                                    $data=$row['data'];

                                    echo '<div class="row menu '.$type.'" id="'.$row['menu_id'].'">
                                    <div class="col-sm-3" style="text-align:center">
                                            <img src="data:image/jpeg;base64,'.base64_encode( $data ).' alt="food" style="width:100%;height:150px">
                                    </div>
                                    <div class="col-sm-6" style="text-align:center">
                                        <h2 class="name">'.$name.'</h2>
                                        <p class="price">$'.$price.'</p>
                                        <p class="type">'.$type.'</p>
                                        <p class="about">'.$about.'</p>
                                    </div>
                                    <div class="col-sm-3" style="padding-top:40px;">
                                        <i class="fa fa-shopping-cart fa-3x"></i>
                                        <form method="post">
                                        <input type="hidden" value='.$row['menu_id'].' name="menu_id">
                                        <input type="submit" name="add" class="add" value="ADD TO CART">
                                        </form>
                                    </div>
                                    </div>';
                                        } ?>
                    </div>
                    <div class="col-sm-2" style="text-align:center">
                        <h3><img  src="./images/new.gif" height="100" width="200"></h3>
                        <?php
                        $stmt = $pdo->prepare('SELECT *  FROM menu ORDER BY day DESC LIMIT 4 ');
                            $stmt->execute(array());
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                            {
                                $title=$row['name'];                
                                echo '<p class="rmenu"><a href="#'.$row['menu_id'].'"><i class="fa fa-angle-double-right"></i>&nbsp;&nbsp;'.$title.'</a><p>';   
                            }
                        ?>
                    </div>  
                </div>

            </div>

            <?php require_once 'footer.php'; ?>
    </body>
    <script>
        function filter(e){
            var x = document.getElementsByClassName('Veg');
	        var y= document.getElementsByClassName('NonVeg');
            if(e.id=='Veg')
            {
                for (var i=0; i<x.length; i+=1)
                    x[i].style.display = 'block';
                for (var i=0; i<y.length; i+=1)
                    y[i].style.display = 'none';    
            }
            if(e.id=='NonVeg')
            {
                for (var i=0; i<y.length; i+=1)
                    y[i].style.display = 'block';
                for (var i=0; i<x.length; i+=1)
                    x[i].style.display = 'none';
            }
            if(e.id=='Both')
            {
                for (var i=0; i<y.length; i+=1)
                    x[i].style.display = 'block';
                for (var i=0; i<x.length; i+=1)
                    y[i].style.display = 'block';
            }
        }
                
    </script>
</html>
<!-- -->