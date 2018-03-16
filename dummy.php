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


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <style type="text/css">
          
      .container {
      
            text-align: center;
            width: 400px;
      }
          
      #homePageContainer {
              
            margin-top: 150px;
          
      }
          
          #containerLoggedInPage {
              
              margin-top: 60px;
              
          }
          
          
      html { 
          
          background: url(background.jpg) no-repeat center center fixed; 
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          background-size: cover;
          
          }
          
          body {
              
              background: none;
              color: #FFF;
              
          }
          
          #logInForm {
              
              display:none;
              
          }
          
          .toggleForms {
              
              font-weight: bold;
              
          }
          
          #diary {
              
              width: 100%;
              height: 100%;
              
          }
          
      </style>
    <title>Hello, world!</title>
  </head>
  <body>
   <div class="container" id="homePageContainer">
      
    <h1>Secret Diary</h1>
          
          <p><strong>Store your thoughts permanently and securely.</strong></p>
          
       <div id="error"><?php echo $error; ?></div>

<form method="post" id = "signUpForm">
    
    <p>Interested? Sign up now.</p>
    
    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">
        
    </fieldset>
    
    <fieldset class="form-group">
    
        <input class="form-control" type="password" name="password" placeholder="Password">
        
    </fieldset>
    
    <div class="checkbox">
    
        <label>
    
        <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
            
        </label>
        
    </div>
    
    <fieldset class="form-group">
    
        <input type="hidden" name="signUp" value="1">
        
        <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
        
    </fieldset>
    
    <p><a class="toggleForms">Log in</a></p>

</form>

<form method="post" id = "logInForm">
    
    <p>Log in using your username and password.</p>
    
    <fieldset class="form-group">

        <input class="form-control" type="email" name="email" placeholder="Your Email">
        
    </fieldset>
    
    <fieldset class="form-group">
    
        <input class="form-control"type="password" name="password" placeholder="Password">
        
    </fieldset>
    
    <div class="checkbox">
    
        <label>
    
            <input type="checkbox" name="stayLoggedIn" value=1> Stay logged in
            
        </label>
        
    </div>
        
        <input type="hidden" name="signUp" value="0">
    
    <fieldset class="form-group">
        
        <input class="btn btn-success" type="submit" name="submit" value="Log In!">
        
    </fieldset>
    
    <p><a class="toggleForms">Sign up</a></p>

</form>
          
      </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

