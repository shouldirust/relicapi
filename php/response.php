<?php

$foo = file_get_contents("php://input");
$json = json_decode($foo);
/*$name = $_POST['name'];
$occupation = $_POST['occupation'];
$body = $_POST['body'];*/

print "<h2>" . $foo.name . "</h2>";
print "<p>" . $foo.body . "</p>";
	
?>