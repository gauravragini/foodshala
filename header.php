<?php if(isset($_SESSION['cust_id'])){ ?>
    <nav class="navbar navbar-inverse navbar-global navbar-fixed-top" id="topbar">
        <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="customerhome.php">FoodShala</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-user">
            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="customerhome.php#ordered">Ordered Items</a></li>
            <li><a href="customerhome.php#cart">Cart</a></li>      
          </ul>
          <ul class="nav navbar-nav navbar-user navbar-right">
            <li><a href="#aboutme"><span class="glyphicon glyphicon-user">&nbsp;</span><?php echo $_SESSION['name']; ?></a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>    
          </ul>
        </div>
      </div>
    </nav>
    <div class="container-fluid">
        <div class="row bg2">
            <br><br>
            <h1>Welcome <span style="color:#3b1270;text-shadow: 4px 4px 6px grey;">"<?php echo $_SESSION['name']; ?>"</span></h1>
            <div class="col-sm-4"></div>
            <div class="col-sm-4"><h3 id="h3">FoodShala</h3></div>
            <div class="col-sm-4"></div>
        </div>
    </div>
<?php } 
 else if(isset($_SESSION['rest_id'])){ ?>
    <nav class="navbar navbar-inverse navbar-global navbar-fixed-top" id="topbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="restauranthome.php">FoodShala</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-user">
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    <li><a href="menu.php">Menu</a></li>
                    <li><a href="restauranthome.php#mymenu">My Menu</a></li>
                    <li><a href="restauranthome.php#orders">Orders</a></li>
                    <li><a href="restauranthome.php#additem">Add Item</a></li>       
                </ul>
                <ul class="nav navbar-nav navbar-user navbar-right">
                    <li><a href="#aboutme"><span class="glyphicon glyphicon-user">&nbsp;</span><?php echo $_SESSION['name']; ?></a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row bg2">
        <br><br>
        <h1>Welcome<span style="color:#3b1270;text-shadow: 4px 4px 6px grey;"> "<?php echo $_SESSION['name']; ?>"</span></h1>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"><h3 id="h3">FoodShala</h3></div>
        <div class="col-sm-4"></div>
        </div>
    </div>
<?php } 
 else { ?>
    <nav class="navbar navbar-inverse navbar-global navbar-fixed-top" id="topbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">FoodShala</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-user">
                    <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    <li><a href="menu.php">Menu</a></li>     
                </ul>
            </div>
        </div>
    </nav>
<?php } ?>


