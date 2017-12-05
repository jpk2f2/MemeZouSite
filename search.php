<?php
$link = mysqli_connect("127.0.0.1", "jkayser","","meme_site");

if(!$link){
	echo "Error: unable to connect to MySQL";
	exit;
}

$stmt = mysqli_prepare($link, "SELECT id FROM Pictures WHERE title=? ");
mysqli_stmt_bind_param($stmt, 's', $searchString);

$searchString=$_POST['search_string'];

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $id);

mysqli_stmt_fetch($stmt);

echo json_encode($id);

mysqli_stmt_close($stmt);




mysqli_close($link);
?>