  <?php
  session_start();
  $error = "";

  if(array_key_exists("logout", $_GET)) {
  	unset($_SESSION);
  	setcookie("id", "", time() - 60*60);
  	$_COOKIE["id"] = "";
  } else if(array_key_exists("id", $_SESSION) OR array_key_exists("id", $_COOKIE)){

  		header("Location: loggedInPage.php");

  }
  	if(array_key_exists("submit", $_POST)) {
  		//EMAIL AND PASSWORD VALIDATION FIRST STEP

  		$link = mysqli_connect("localhost", "root", "", "secretd");

  		if(mysqli_connect_error()) {

  			die ("Database connection failure");
  		}

  		

  		if( !$_POST['email']) {
  			$error .= "An email address is required<br>";
  		}

  		if( !$_POST['password']) {
  			$error .= "A password is required<br>";
  		}

  		if($error != "") {
  			$error = "<p>There were no errors in your form!</p>". $error;
  		}
  		else {


  			if($_POST['signUp'] == '1') {





  			
  			//Check if there are any users in the dadtabase
  			$query  ="SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

  			$result = mysqli_query($link, $query);

  			if(mysqli_num_rows($result) > 0) {
  				$error =  "That email address is already taken";
  			}
  			else {
  				$query  = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";


  				//Check if email is already taken
  				if( !mysqli_query($link, $query)) {
  					$error = "<p>could not sign up up bruh, try again!</p>";
  				}
  				else {

  					$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1"; 

  					mysqli_query($link, $query);
  					$_SESSION['id'] = mysqli_insert_id($link);

  					if($_POST['stayLoggedIn'] == '1') {
  						setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
  					}
  					header("Location: loggedInPage.php");
  				}
  			} 
  		}else {
  				//Check if username and password are correct from the database.
  			$query = "SELECT * from `users` WHERE email='".mysqli_real_escape_string($link, $_POST['email'])."'";

  			$result = mysqli_query($link, $query);
  			$row = mysqli_fetch_array($result);
  			if(isset($row)) {
  				$hashedPassword = md5(md5($row['id']).$_POST['password']);

  				if($hashedPassword == $row['password']) {
  					$_SESSION['id'] = $row['id'];

  					if($_POST['stayLoggedIn'] == '1') {
  						setcookie("id", $row['id'], time() + 60*60*24*365);
  					}
  					header("Location: loggedInPage.php");

  				} else {
  					$error = "Email/Password combination could not be found";
  				}

  			} else {
  				$error = "That email/password could not be found";
  			}
  			}
  			
  		}
  		//EMAIL AND PASSWORD VALIDATION OVER
  	}
  ?>

  <div id="error"><?php echo $error; ?></div>




<!-- SIGNUP FORM -->
  <form method = "post">
  	
  	<input type="email" name="email" placeholder="Your Email">
  	<input type="password" name="password" placeholder="Your password">
  	<input type="checkbox" name = "stayLoggedIn" value="1">
  	<input type="hidden" name="signUp" value="1">
  	<input type="submit" name="submit" value ="SignUp">

  </form>
<!-- LOGIN FORM -->
  <form method = "post">
  	
  	<input type="email" name="email" placeholder="Your Email">
  	<input type="password" name="password" placeholder="Your password">
  	<input type="checkbox" name = "stayLoggedIn" value="1">
  	<input type="hidden" name="signUp" value="0">

  	<input type="submit" name="submit" value ="Log In">

  </form>