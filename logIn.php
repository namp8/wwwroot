<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="UNITED production system" />
        <meta name="author" content="summIT Solution | Natalia Montanez" />
        <title>United Production System | Sign In</title>
        <!-- BOOTSTRAP CORE CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="assets/css/style.css" rel="stylesheet">
    </head>

        <body class="loginBody">

        <div class="container">
            <div class="row vertical-offset-50">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <img src="img/logo.png" alt="" width="100%"/>
                            <h1 class="panel-title">Please sign in</h1>
                        </div>
                        <div class="panel-body">
                            <form class="form-signin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" name="loginform" id="loginform">
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                                    </label>
                                </div>
                                <button class="btn btn-lg btn-info btn-block" type="submit">Sign in</button>
                                <h6 id="error" style="color: #ff0000;"></h6>
                            </fieldset>    
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
        </div>    
            
       
                
<?php
    include_once "base.php";
    if(!empty($_POST['username']) && !empty($_POST['password'])):
        include_once 'inc/class.users.inc.php';
        $users = new Users($db);
        if($users->accountLogin()===TRUE):
            echo "<meta http-equiv='refresh' content='0;index.php'>";
            exit;
        else:
            echo '<script>document.getElementById("error").innerHTML = "Sign in failed, Please try again!";</script>';
        endif;
    endif;
?>


        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
        <!-- CORE JQUERY -->
        <script src="assets/js/jquery-3.1.0.min.js"></script>
        <!-- BOOTSTRAP SCRIPTS -->
        <script src="assets/js/bootstrap.min.js"></script>
    </body>

    </html>
