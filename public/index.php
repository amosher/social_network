<?php
	include("../private/initialize.php");
	// Commong PHP code used for configuring the database
	
?>
<?php
	$name_error = "";
	$username_error = "";
	$email_error = "";
	$password_error = "";
	$gender_error = "";

	if(!empty($_POST['signup_btn'])){
		$name = $_POST['full_name'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$gender = $_POST['gender'];
		
		$all_data_valid = true;
		
		
		if(empty($name)){
			$name_error = "Please enter your name";
			$all_data_valid = false;
		}
		
		if(empty($username)){
			$username_error = "Please enter a username";
			$all_data_valid = false;
		}
		
		if(empty($email)){
			$email_error = "Please enter your email address";
			$all_data_valid = false;
		}
		
		if(empty($password)){
			$password_error = "Please enter a password";
			$all_data_valid = false;
		}
		
		if(empty($gender)){
			$gender_error = "Please select your gender";
			$all_data_valid = false;
		}
		
		if($all_data_valid){
			// Insert the user into the db
			try {
			    // Create Connection
			    $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=". DBNAME, USERNAME_DB, PASSWORD_DB);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    // Create the SQL command for inserting new list into the lists table and use :parameters if needed
			    $sql = "INSERT INTO users (fullname, username, password, email, gender) VALUES (:name, :username, :password, :email, :gender)";
			    
			    // Prepare the execution of the SQL command
			    $stmt = $conn->prepare($sql);
				
				$password_hashed = password_hash($password, PASSWORD_DEFAULT);
				
			    // Bind all parameters if there are any
			    $stmt->bindParam(':name', $name);
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':password', $password_hashed);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':gender', $gender);
			 	
			 	// Execute the SQL command in the DB server. This line does the actual inserting of the data
			    $stmt->execute();

			    echo "You have been signed up" . $conn->lastInsertId();
			}
			catch(PDOException $e)
			{
				echo "Error: " . $e->getMessage();
			}
		}
	}
	
?>
<?php
$usernamelogin_error = "";
$passwordlogin_error = "";

if(!empty($_POST['login_btn'])){
	$username = $_POST['user_email'];
	$password = $_POST['password'];

	$all_data_valid = true;


	if(empty($username)){
		$usernamelogin_error = "Please enter your email or username";
		$all_data_valid = false;
	}

	if(empty($password)){
		$passwordlogin_error = "Please enter your password";
		$all_data_valid = false;
	}
	
	if($all_data_valid){
			// Insert the user into the db
			try {
			    // Create Connection
			    $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME, USERNAME_DB, PASSWORD_DB);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			    // Create the SQL command for inserting new list into the lists table and use :parameters if needed
			    $sql = "SELECT * FROM users WHERE username=:email OR email=:email";
			    
			    // Prepare the execution of the SQL command
			    $stmt = $conn->prepare($sql);
				
			    // Bind all parameters if there are any
			    $stmt->bindParam(':email', $username);			
			 	
			 	// Execute the SQL command in the DB server. This line does the actual inserting of the data
			    $stmt->execute();
				
				$user = $stmt->fetch();
				if(!$user){
					echo('Invalid credentials');
				}
				else{
					if(password_verify($password, $user['password'])){
						// Log in the user
						echo('Valid Credentials');
						$_SESSION['logged_in'] = true;
						$_SESSION['user'] = $user;
						
						header("Location: dashboard.php");
					}
					else {
						echo('Invalid credentials');
					}
				}

			    echo "You have been logged in" . $conn->lastInsertId();
			}
			catch(PDOException $e)
			{
				echo "Error: " . $e->getMessage();
			}
		}
}
?>
<?php
	if(userLoggedIn()){
		$location="dashboard.php";
		redirect($location);
	}

?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>landing page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
	h1{
		margin-top: -1px !important;
	}
	#login-dp{
    min-width: 250px;
    padding: 14px 14px 0;
    overflow:hidden;
    background-color:rgba(255,255,255,.8);
}
#login-dp .help-block{
    font-size:12px    
}
#login-dp .bottom{
    background-color:rgba(255,255,255,.8);
    border-top:1px solid #ddd;
    clear:both;
    padding:14px;
}
#login-dp .social-buttons{
    margin:12px 0    
}
#login-dp .social-buttons a{
    width: 100%;
}
#login-dp .form-group {
    margin-bottom: 10px;
}
.btn-fb{
    color: #fff;
    background-color:#0F9D58;
}
.btn-fb:hover{
    color: #fff;
    background-color:#496ebc 
}
.btn-tw{
    color: #fff;
    background-color:#55acee;
}
.btn-tw:hover{
    color: #fff;
    background-color:#59b5fa;
}
@media(max-width:768px){
    #login-dp{
        background-color: inherit;
        color: #fff;
    }
    #login-dp .bottom{
        background-color: inherit;
        border-top:0 none;
    }
}
	.navbar-brand{
		padding: 5px 15px;
	}
	.navbar-default, .navbar-brand{
		background-color: #3c8dbc !important;
		color: white !important;
	}
	.navbar-default, .navbar-brand:hover{
		color: white !important;
	}
	.navbar-default, .navbar-text, .text-color{
		color: white !important;
	}
	.navbar-default, .text-black{
		color: black !important;
		
	}
	.navbar-default .navbar-nav > li > a{
		color: white !important;
	}
	.navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus{
		background-color: #3c8dbc !important;
		
	}
	a:hover{
		opacity: .5;
	}
	.iphone{
		width: 45%;
		height: auto;
			
	}
	body{
		overflow: auto;
	}
	</style>
</head>

<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        <h1>Dex</h1>
      </a>
      <ul class="nav navbar-nav navbar-right">
        <li><p class="navbar-text text-color">Already have an account?</p></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
			<ul id="login-dp" class="dropdown-menu">
				<li>
					 <div class="row">
							<div class="col-md-12 text-black">
								Login via
								<div class="social-buttons">
									<a href="#" class="btn btn-fb"><i class="fa fa-facebook"></i>Google</a>
									
								</div>
                                or
								 <form class="form" role="form" method="post" accept-charset="UTF-8" id="login-nav">
										<div class="form-group">
											 <label class="sr-only" for="exampleInputEmail2">Email address</label>
											 <input type="text" name="user_email" class="form-control" id="exampleInputEmail2" placeholder="Email or Username" required="">
											 <span class="text-danger"><?php echo($usernamelogin_error) ?></span>
										</div>
										<div class="form-group">
											 <label class="sr-only" for="exampleInputPassword2">Password</label>
											 
											 <input type="password" name="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required="">
                                            <span class="text-danger"><?php echo($passwordlogin_error) ?></span>
                                             <div class="help-block text-right"><a href="">Forget the password ?</a></div>
										</div>
										<div class="form-group">
											 <input type="submit" class="btn btn-primary btn-block" name="login_btn" value="Log In"/>
										</div>
										<div class="checkbox">
											 <label>
											 <input type="checkbox"> keep me logged-in
											 </label>
										</div>
								 </form>
							</div>
							
					 </div>
				</li>
			</ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


	<div class="container">
		<div class="row">
			<div class="col-xs-hidden col-md-6">
				<img class="iphone" src='images/iphone.png'/>
			</div>
			<div class="col-md-6 col-xs-12">
				<form method="post">
					<div class="form-group">
						<input type="text" placeholder="Full Name" class="form-control" name="full_name">
						<span class="text-danger"><?php echo($name_error) ?></span>
					</div>
					<div class="form-group">
						<input type="text" placeholder="Username" class="form-control" name="username">
						<span class="text-danger"><?php echo($username_error) ?></span>
					</div>
					<div class="form-group">
						<input type="email" placeholder="Email" class="form-control" name="email">
						<span class="text-danger"><?php echo($email_error) ?></span>
					</div>
					<div class="form-group">
						<input type="password" placeholder="Password" class="form-control" name="password">
						<span class="text-danger"><?php echo($password_error) ?></span>
					</div>
					<div class="radio">
    					<label><input value="male" name="gender" type="radio">Male</label>
    					<label><input value="female" name="gender" type="radio">Female</label><br/>
    					<span class="text-danger"><?php echo($gender_error) ?></span>
  					</div>
					<div class="form-group">
						<input type="submit" value="Sign Up" class="btn btn-danger" name="signup_btn">
					</div>
				</form>
			</div>
		</div>
	</div>

<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
