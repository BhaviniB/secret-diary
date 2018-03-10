<?php
if(array_key_exists("submit",$_POST))
{
	
	error="";
	
	if(!_POST['email'])
	{
		$error.="An email address is required<br>";
	}
 	if(!_POST['password'])
	{
		$error.="A password address is required<br>";
	}
 	
	if ($error!="")
	{
	$error="<p>There were errors in your form:</p>".$error;	
		
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
<input type="checkbox" name="stayLoggedIn" value=1>
<input type="submit" name="submit" value="Sign Up!"> 
</input>
</form>