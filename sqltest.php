<?php
// mysqli
$mysqli = new mysqli("localhost", "jkayser", "", "meme_site");
$result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
$row = $result->fetch_assoc();
echo htmlentities($row['_message']);

?>