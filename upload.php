<?php
	echo <<<_END
		<html><head><title>PHP Form Upload</title></head><body>
		<form method='post' action='upload.php' enctype='multipart/form-data'>
		Select a JPG or PNG File:
		<input type='file' name='filename' size='10' required>
		Title:
		<input type='text' name='title' required>
		Type:
		<select name="type" required>
  			<option value="template">template</option>
  			<option value="meme" disabled>meme</option>
		</select>
		Description:
		<textarea name="description" rows="10" cols="40">
		</textarea>
		<input type='submit' value='Upload'></form>
_END;

	if($_FILES)
	{
		$name = $_FILES['filename']['name'];

		switch($_FILES['filename']['type'])
		{
			case 'image/jpeg': $ext = 'jpg'; break;
			case 'image/png':  $ext = 'png'; break;
			default:		   $ext = '';    break;
		}
		if ($ext){
			$n =  __DIR__ . "/memes/image.$ext";
			if(copy($_FILES['filename']['tmp_name'], $n)){
				echo "Uploaded image '$name' as '$n':<br>";
				echo "<img src='$n'>";
			}else{
				echo "Upload failed";
			}
		}
		else echo "'$name' is not an accepted image file";
	}
	else echo "No image file has been uploaded";
	echo "</body></html>";

?>