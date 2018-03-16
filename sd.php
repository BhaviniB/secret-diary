<?php
	session_start();
		$error="";
		
		if(array_key_exists("logout",$_GET))
		{
			
			unset($_SESSION);
			setcookie("id","",time()-60*60);
			$_COOKIE["id"]="";
			
		}
		else if(array_key_exists(("id",$_SESSION) AND$_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id'])
			
		{
			header("location: loggedinpage.php");
			
			
		}
		
		
if(array_key_exists("submit",$_POST))

{
	$username="Bhavini";
	$password="Batra";
	$db="SD";

	$link=new mysqli ('localhost',$user,$pass,$db)
 or die("Unable to connect");
	
	if(!$_POST['email'])
	{
		$error.="An email address is required<br>";
	}
 	if(!$_POST['password'])
	{
		
	$error.="A password is required<br>";
	}
 	
	if ($error!="")
	{
	$error="<p>There were errors in your form:</p>".$error;	
		
	}else{
		if($_POST['signUp']='1')
		{
		
		$query="SELECT id FROM users WHERE email='".mysqli_real_escape_string($_POST['email'])."'LIMIT1";
		$result=mysqli_query($link,$query);
		if(mysqli_num_rows($result)>0){
			$error="That email address is taken";
		}
		else{
			$query="INSERT INTO users('email','password')VALUES('".mysqli_real_escape_string($_POST['password'])."','".mysqli_real_escape_string($_POST['password'])."')"; 
			
		if(!mysqli_query($link,$query))
		{
			$error="<p>Could not sign you up</p>";
		}
		else{
			
		$query="UPDATE users SET password='".md5(md5(mysqli_insert_id($link)).$_POST['password'])."'WHERE id=".mysqli_insert_id($link)."LIMIT 1";
		mysqli_query($link,$query);
	    $_SESSION['id']=mysqli_insert_id($link);
		if($_POST['stayLoggedIn']='1')
		{
			
			setcookie("id",mysqli_insert_id($link),time()+60*60*24*365);
			
			 
		}
			header("Location: loggedinpage.php");
		}
	}
	
  }
  //if the user has got the username and password correct
  else{
	  $query="SELECT id FROM users WHERE email='"mysqli_real_escape_string($link ,$_POST['email'])."'";
	  
	  $result= mysqli_query($link,$query);
	  $row=mysqli_fetch_array($result);
	  if(isset($row))
	  {
		  $hashedPassword= md5(md5($row['id']).$_POST['password']);
		  if($hashedPassword==$$row['password'])
		  {
			  $_SESSION['id']=$row['id'];
			  if($_POST['stayLoggedIn']='1')
		{
			
			setcookie("id",$row['id'],time()+60*60*24*365);
			
			 
		}
			header("Location: loggedinpage.php");
			  
		  }
	  else
	  {
		  $error="That email/passowrd combination could not be found";		

	  }
	  

	  }else{
$error="That email/passowrd combination could not be found";		

		
	  }
	  
}

}


?>
<div id="error">
<?php
echo $error;
?>
</div>
<form method="post">

<input type="email" name="email" placeholder="Your Email">
<input type="password" name="password" placeholder="Password">
<input type="checkbox" name="stayLoggedIn" value="1">
<input type="hidden" name="signUp" value="1">
<input type="submit" name="submit" value="Sign Up!"> 
</input>
</form>




<form method="post">

<input type="email" name="email" placeholder="Your Email">
<input type="password" name="password" placeholder="Password">
<input type="hidden" name="signUp" value="0">
<input type="checkbox" name="stayLoggedIn" value=1>
<input type="submit" name="submit" value="Log In!"> 
</input>
</form>