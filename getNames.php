<?php
$link = mysqli_connect("127.0.0.1", "jkayser","","meme_site");

if(!$link){
	echo "Error: unable to connect to MySQL";
	exit;
}

//$stmt = mysqli_prepare($link, "SELECT id FROM Pictures WHERE title=? ");
$stmt = mysqli_prepare($link, "SELECT id,title FROM Pictures");
//mysqli_stmt_bind_param($stmt, 's', $searchString);

//$searchString=$_POST['search_string'];

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $id, $title);

while(mysqli_stmt_fetch($stmt)){
	echo "<li><a href='memes/","$id",".jpg' onchange='getPicture($id)'>$title</a></li>";
}

//echo json_encode($id);

mysqli_stmt_close($stmt);




mysqli_close($link);



?>