
<?php if(isset($_SESSION['cust_id'])){ ?>
    <div class="footer" id="aboutme">
        <?php
            $stmt = $pdo->prepare('SELECT * From customers where cust_id=:cid');
            $stmt->execute(array(
            ':cid' => $_SESSION['cust_id'])); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<div class="row">
                    <div class="col-sm-4"></div> 
                    <div class="col-sm-4">
                        <h2 style="color: white;
                        text-shadow: 2px 2px 4px #000000;text-align:center;">'.$row['name'].'</h2>
                        <h5><i class="fa fa-home"></i> '.$row['address'].'</h5>
                        <h5><i class="fa fa-mobile"></i> '.$row['phone'].'</h5>
                        <h5><i class="fa fa-envelope"></i> '.$row['email'].'</h5>   
                    </div>
                    <div class="col-sm-4"></div>   
                </div>'; 
        ?>
    </div> 
<?php } 
 else if(isset($_SESSION['rest_id'])){ ?>
    <div class="footer" id="aboutme">
        <?php
            $stmt = $pdo->prepare('SELECT * From restaurants where rest_id=:rid');
            $stmt->execute(array(
            ':rid' => $_SESSION['rest_id'])); 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<div class="row">
                    <div class="col-sm-1"></div>  
                    <div class="col-sm-3" style="text-align:left;"><h3>About:</h3><h5>'.$row['about'].'</h5></div>  
                    <div class="col-sm-4">
                        <h2 style="color: white;text-shadow: 2px 2px 4px #000000;text-align:center; ">'.$row['name'].'</h2>
                        <h5><i class="fa fa-home"></i> '.$row['location'].'</h5>
                        <h5><i class="fa fa-mobile"></i> '.$row['phone'].'</h5>
                        <h5><i class="fa fa-envelope"></i> '.$row['email'].'</h5>     
                    </div>
                    <div class="col-sm-4"></div> 
                </div>';
        ?>
    </div>
<?php } ?>

<footer style="text-align: center;padding: 10px;background-color:lightgrey;">
    <p>Need Help ? <a href="mailto:hosth914@gmail.com">Mail Us </a><i class="fa fa-envelope"></i></p>
    <p style="font-style: italic; color: grey; margin:0px">&copy; Created By Ragini Gaurav</p>
</footer>