<?php
if(isset($data['success'])){
	echo $data['success'];
}

if(isset($error)){
	foreach($error as $error){
		echo "<p>$error</p>";
	}
}
?>