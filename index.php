<?php
session_start();
if(isset($_POST['user_name']) AND isset($_POST['password'])) 
{
	include'./include/connect.php';	
	
	$un = $_POST['user_name'];
	$pw = md5($_POST['password']);						
						
	$stmt = $pdo->prepare('SELECT * FROM user_info WHERE user_name=? AND password=?');
	$stmt->execute([$un, $pw]);
	$user = $stmt->fetch();	
		
	if(!($user)) 
	$err = 'Authentication Failed. Please try with valid credentials';	
	else
	{
		$_SESSION['auth'] = 'ON';
		header("location:view.php");
	}
	
}

if(isset($_SESSION['auth']) AND isset($_GET['log']) AND $_GET['log'] == 'out') 
{
	foreach ( $_SESSION as $key => $value )
	{
		unset($key);		
	}	
	
	session_destroy();
	
}

?>
<!DOCTYPE html>
<html class="bootstrap-admin-vertical-centered">
    <head>
        <title>Login page | Ventano</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap -->
        <link rel="stylesheet" media="screen" href="css/bootstrap.min.css">
        
        <!-- Custom styles -->
        <link rel="stylesheet" media="screen" href="css/style.css">

        
    </head>
    <body class="bootstrap-admin-without-padding">
        <div class="container">
            <div class="row">                
				
                <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>" class="login-form">
                    <?php echo $txt = isset($err) ? '<h5>'.$err.'</h5>' : ''; ?>
					<h1>Login</h1>
                    <div class="form-group">
                        <input class="form-control" type="text" name="user_name" placeholder="User Name">
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                    </div>
                    
                    <button class="btn btn-lg btn-primary" type="submit">Submit</button>
                </form>
            </div>
        </div>        
        
    </body>
</html>
