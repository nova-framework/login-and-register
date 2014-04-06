<h1>Login</h1>

<?php
if(isset($error)){
	foreach($error as $error){
		echo "<p>$error</p>";
	}
}
?>

<form action='' method='post'>
<p>Username<br /><input type='text' name='username' value=''></p>
<p>Password<br /><input type='password' name='password' value=''></p>
<p><input type='submit' name='submit' value='Login'></p>
</form>