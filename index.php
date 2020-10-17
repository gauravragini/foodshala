<?php   
    require_once "pdo.php";
    session_start();
    $salt = 'XyZzy12*_';

    //for login
    if ( isset($_POST['email']) && isset($_POST['pass']) ) {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT rest_id,name FROM restaurants WHERE email = :em AND pass = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $pdo->prepare('SELECT cust_id,name FROM customers WHERE email = :em AND pass = :pw');
        $stmt2->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        
        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['rest_id'] = $row['rest_id'];
            header("Location: restauranthome.php");
            return;
        }
        else if($row2 !== false){
            $_SESSION['name'] = $row2['name'];
            $_SESSION['cust_id'] = $row2['cust_id'];
            header("Location: customerhome.php");
            return;
        }
        else{
            error_log("Login fail ".$_POST['email']." $check");
            echo '<script language="javascript">';
            echo 'alert("incorrect email id or password")';
            echo '</script>';     
        }

    }

    //for restaurant sign up
    if ( isset($_POST['restmail']) ) {
        $pass = hash('md5', $salt.$_POST['password']);
        $stmt = $pdo->prepare('INSERT INTO restaurants(name,email,pass,phone,location,about) Values(:n, :e, :p, :ph,:l, :a)');
        $stmt->execute(array( 
            ':n' => $_POST['name'] ,
            ':e' => $_POST['restmail'],
            ':p' =>$pass, 
            ':ph'=>$_POST['phone'], 
            ':l'=>$_POST['location'],
            
            ':a'=>$_POST['about'] 
        ));
        
        $stmt = $pdo->prepare('SELECT rest_id,name FROM restaurants WHERE email = :em AND pass = :pw');
        $stmt->execute(array( ':em' => $_POST['restmail'], ':pw' => $pass));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['name'] = $row['name'];
        $_SESSION['rest_id'] = $row['rest_id'];
        header("Location: restauranthome.php"); 
    }

    //for customer signup
    if ( isset($_POST['custmail']) ) {
        $pass = hash('md5', $salt.$_POST['password']);
        $stmt = $pdo->prepare('INSERT INTO customers(name,email,pass,phone,address,preference) Values(:n, :e, :p, :ph,:ad, :pre)');
        $stmt->execute(array( 
            ':n' => $_POST['name'] ,
            ':e' => $_POST['custmail'],
            ':p' =>$pass, 
            ':ph'=>$_POST['phone'], 
            ':ad'=>$_POST['address'],
            ':pre'=>$_POST['preference'] 
        ));
    
        $stmt = $pdo->prepare('SELECT cust_id,name FROM customers WHERE email = :em AND pass = :pw');
        $stmt->execute(array( ':em' => $_POST['custmail'], ':pw' => $pass));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['name'] = $row['name'];
        $_SESSION['cust_id'] = $row['cust_id'];
        header("Location: customerhome.php"); 
    }

    if(isset($_POST['order'])){
        if(!isset($_SESSION['cust_id'])){
            echo '<script language="javascript">';
            echo 'alert("Not logged In");';
            echo 'window.location.href = "index.php";';
            echo '</script>'; 
        }

        $stmt = $pdo->prepare('INSERT INTO cart(cust_id,menu_id) Values(:c, :m)');
        $stmt->execute(array( 
            ':c' => $_SESSION['cust_id'] ,
            ':m' => $_POST['menu_id']
            
        )); 
            echo '<script language="javascript">';
            echo 'window.location.href = "customerhome.php";';
            echo 'alert("added to cart");';
            echo '</script>';

    }        
?>

<!DOCTYPE html>
<html>
    
    <?php require_once 'head.php'; ?>
    <body id="body">

        <!-- Heading section-->
        <div class="intro">
            <img  src="./images/1.jpg" id="background" width="100%" height="350">
            <?php
                if(!isset($_SESSION['name'])){
                    echo '<a href="#myModalLogin" class="button3 log" data-toggle="modal">Log in </a>';          
                    echo '<a href="#myModalSignup" class="button3 sign" > Sign in</a>';
                }
                else if(isset($_SESSION['rest_id'])){
                    echo '<script language="javascript">';
                    echo 'window.location.href = "restauranthome.php";';
                    echo '</script>';
                }
                else if(isset($_SESSION['cust_id'])){
                    echo '<script language="javascript">';
                    echo 'window.location.href = "customerhome.php";';
                    echo '</script>';
                }
            ?>
            <div class="content">
                <h1>FOODSHALA</h1>
                <h2>Ending Hunger !!! </h2>
            </div>
        </div>

        <br><br>

        <div class="container">
            <div class="row">
                <h1 class="h1" style="color:white">Our Latest Collection</h1>
                <?php
                    $stmt = $pdo->prepare('SELECT * FROM menu ORDER BY day DESC LIMIT 3');
                    $stmt->execute(array()); 
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    { 
                        $name=$row['name'];
                        $type=$row['type'];
                        $price=$row['price'];
                        $about=$row['about'];
                        $day=$row['day'];
                        $data=$row['data'];

                        echo '<div class="col-xs-8 col-sm-6 col-md-3">
                            <div class="card">
                                <div class="foodimage">
                                    <img src="data:image/jpeg;base64,'.base64_encode( $data ).' alt="food" style="width:100%;height:200px">
                                    <div class="overlay">'.$name.'</div>                        
                                </div> 
                                <p class="price">'.$price.'</p>
                                
                                <form method="post">
                                <input type="hidden" value='.$row['menu_id'].' name="menu_id">
                                <button type="submit" name="order">Order Now</button>
                                </form>
                            </div>
                        </div>';
                    }
                ?>   
                <div class="col-xs-8 col-sm-6 col-md-3">
                    <div class="card">
                        <div class="foodimage">
                            <a href="menu.php">
                                <div style="width:100%;height:290px; background-color:grey;padding:70px;color:white"> <h1>View All Menu</h1></div>
                            </a>                        
                        </div> 
                    </div>
                </div>  
            </div>

            <br><br><hr><br><br>

            <div class="row " id="myModalSignup" >
                
                <h1 class="h1" style="color:white">Join Us Now!!!!!!</h1><br>
                <div class="col-xs-1"></div>
                <div class="col-xs-11" style="text-align:center">
                    <div class="col-sm-12 col-md-5 x">
                        <img src="./images/cust.jpg" alt="" class="center">
                        <a href="#customerSignup" class="signbtn"  data-toggle="modal"><h3>Signup as customer</h3></a>
                    </div>				   
                    <div class="col-sm-12 col-md-5 x" >
                        <img src="./images/chef.jpg" alt="" class="center" >
                        <a href="#restaurantSignup" class="signbtn" data-toggle="modal"> <h3>Signup as Resautrant</h3></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- login modal-->
        <div id="myModalLogin" class="modal fade">
            <div class="modal-dialog modal-login">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="avatar">
                            <i class="fa fa-user " aria-hidden="true"></i>
                        </div>				
                        <h4 class="modal-title">Member Login</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form  method="post" name="login">
                            <div class="form-group">
                                <input type="text" class="form-control" name="email" id="nam" placeholder="Username" required="required">		
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="pass" id="id_1723" placeholder="Password" required="required">	
                            </div>        
                            <div class="form-group button2">
                                <button type="submit" class="btn btn-primary btn-lg  login-btn">Login</button>
                                <button type="reset" class="btn btn-primary btn-lg  login-btn">Clear</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Signup modals-->
        <div id="customerSignup" class="modal fade">
            <div class="modal-dialog modal-login">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="avatar">
                            <i class="fa fa-user " aria-hidden="true"></i>
                        </div>				
                        <h4 class="modal-title">Create an Account</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form  method="post" name="signincust">
                            <div class="form-group">
                                <label for="nam">Name : </label>
                                <input type="text" class="form-control" name="name" id="nam" placeholder="Ragini Gaurav" required="required">		
                            </div>
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input type="text" class="form-control" name="custmail" id="email" placeholder="abc@xyz" required="required">		
                            </div>
                            <div class="form-group">
                                <label for="phone">Contact Number : </label>
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="10 digit number" >		
                            </div>
                            <div class="form-group">
                                <label>Preference : </label><br>
                                <input type="radio" name="preference" value="Veg" >&nbsp;Veg&nbsp;&nbsp;
                                <input type="radio" name="preference" value="NonVeg" >&nbsp;NonVeg&nbsp;&nbsp;
                                <input type="radio" name="preference" value="Both" >&nbsp;Both
                            </div>
                            <div class="form-group">
                                <label for="prof">Address : </label>
                                <input type="text" class="form-control" name="address" id="prof" placeholder="xhwdgc" >		
                            </div>
                            <div class="form-group">
                                <label for="pass">Password : </label>
                                <input type="password" class="form-control" name="password" id="pass" placeholder="Xy_12gK99" required="required">		
                            </div>  
                            <div class="form-group button2">
                                <button type="submit" class="btn btn-primary btn-lg  login-btn">Sign in</button>
                                <button type="reset" class="btn btn-primary btn-lg  login-btn">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="restaurantSignup"class="modal fade">
            <div class="modal-dialog modal-login">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="avatar">
                            <i class="fa fa-user " aria-hidden="true"></i>
                        </div>				
                        <h4 class="modal-title">Create an Account</h4>	
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form  method="post" name="signinrest">
                            <div class="form-group">
                                <label for="nam">Name of Restaurant : </label>
                                <input type="text" class="form-control" name="name" id="nam" placeholder="XYZ" required="required">		
                            </div>
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input type="text" class="form-control" name="restmail" id="email" placeholder="abc@xyz" required="required">		
                            </div>
                            <div class="form-group">
                                <label for="phone">Contact Number : </label>
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="10 digit number" >		
                            </div>
                            
                            <div class="form-group">
                                <label for="prof">location : </label>
                                <input type="text" class="form-control" name="location" id="loc" placeholder="xyz India" >		
                            </div>
                            <div class="form-group">
                                <label for="pass">About: </label>
                                <input type="text" class="form-control" name="about" id="pass" placeholder="about....">		
                            </div>  
                            <div class="form-group">
                                <label for="pass">Password : </label>
                                <input type="password" class="form-control" name="password" id="pass" placeholder="Xy_12gK99" required="required">		
                            </div>  
                            <div class="form-group button2">
                                <button type="submit" class="btn btn-primary btn-lg  login-btn">Sign in</button>
                                <button type="reset" class="btn btn-primary btn-lg  login-btn">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Section--> 
        <br><br>        
        <?php require_once 'footer.php'; ?>

    </body>

    
</html>
