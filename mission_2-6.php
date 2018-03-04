<?php

//save in kadai2-2.txt
$filename = 'kadai2-2.txt';


//when $filename does not exist
if(is_file($filename) == FALSE){
	$num = 1;
//when $filename does exist
}else{
	//put each line of $filename into array "$lines"
	$lines = file($filename);
	//put factors of last line 
	$fac = explode("<>", $lines[count($lines) - 1]);
	$num = $fac[0] + 1;
}


//receive name and coment
if(!empty($_GET['comment']) || !empty($_GET['password'])){
	$name = $_GET['name'];
	$comment = $_GET['comment'];
	$hidden = $_GET['hidden'];
	$password = $_GET['password'];

	$comment = str_replace("\n", "<br>", $comment);
	if(empty($name)){$name = "Joe Doe";}
	if(empty($comment)){echo "<FONT COLOR=\"RED\">Comment is not filled in.\n</FONT>";
	}else if(empty($password)){echo "<FONT COLOR=\"RED\">Password is not set.\n</FONT>";
	}else{
	//when hidden empty = adding mode, not empty = editing mode.
	//$hidden = target line number for editing	
	
		if(empty($hidden)){
	
			$fp = fopen($filename, 'a');
			fwrite($fp, $num."<>".$name."<>".$comment."<>".date('Y/m/d H:i:s')."<>".$password."\n");
			fclose($fp);
		}else{
			$elines = file($filename);

			$fp = fopen($filename, 'w');
			foreach($elines as $value){
				//break each line into array "$efac"
				$efac = explode("<>", $value);
				//if number is the target number
				if($efac[0] == $hidden){
					$efac[1] = $name;
					$efac[2] = $comment;
					$efac[3] = date('Y/m/d H:i:s');
				}
				fwrite($fp, $efac[0]."<>".$efac[1]."<>".$efac[2]."<>".$efac[3]."<>".$password."\n");
			}
			fclose($fp);
		}
	}
}else if(!empty($_GET['name']) && empty($_GET['comment']) && empty($_GET['password'])){
	echo "<FONT COLOR =\"RED\">Comment and password have to be filled in.\n</FONT>";
}



//isset(): returns TRUE when var is set, FALSE when not set
if(!empty($_POST['delete'])){
	
	$delete = $_POST['delete'];
	//put each line of $filename into array "$lines"
	$dlines = file($filename);

	$fp = fopen($filename, 'w');
	foreach($dlines as $value){
		//break each line into array "$dfac"
		$dfac = explode("<>", $value);
		//if number is not the target number, write in
		if($dfac[0] != $delete){
			fwrite($fp, $value);
		//if number is the target number, delete
		}else{
			$dfac[4] = str_replace("\n", "", $dfac[4]);
			if($_POST['password'] === $dfac[4]){
				fwrite($fp, $dfac[0]." This comment has been deleted.\n");
			}else{
				fwrite($fp, $value);
				echo "<FONT COLOR=\"RED\">Password is incorrect.\n</FONT>";
			}
		}
	}
	fclose($fp);
}


if(!empty($_POST['edit'])){
	$edit = $_POST['edit'];
	//put each line of $filename into array "$elines"
	$elines = file($filename);

	foreach($elines as $value){
		//break each line into array "$efac"
		$efac = explode("<>", $value);
		//if number is the target number
		if($efac[0] == $edit){
			$efac2[4] = str_replace("\n", "", $efac[4]);
			if($_POST['password'] === $efac2[4]){
				$ename = $efac[1];
				$ecomment = $efac[2];
			}else{
				echo "<FONT COLOR=\"RED\">Password is incorrect.\n</FONT>";
			}
		}
	}
//	fclose($fp);
}

?>



<html>
<head>
	<title>Programming Blog</title>


</head>

<body>
<h1>Programming Blog<h1>

<!_make a form_>
<!_send name and comment_>

<form action = "mission_2-6.php" method = "get">
<font size = "3">Name:<br/></font>
<input type = "text" name = "name" 
	value = "<?php if(isset($ename))echo $ename; ?>"><br/>
Comment:<br/>
<textarea name = "comment" rows = "4" cols = "40">
<?php if(isset($ecomment)){$ecomment = str_replace("<br>", "\n", $ecomment); echo $ecomment;}?></textarea>
<br/>
<!_if editing hidden tag is 1_>
<input type = "hidden" name = "hidden" 
	value = "<?php if(isset($ename))echo $edit ?>">
Password:<br>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Submit">
</form>



<!_make a delete form_>

<form action = "mission_2-6.php" method = "post" 
	>
Delete Number:<br>
<input type = "text" name = "delete"><br/>
Password:<br>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Delete"><br/>
</form>


<!_make a form for editing_>

<form action = "mission_2-6.php" method = "post">
Edit Number:<br>
<input type = "text" name = "edit"><br/>
Password:<br>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Edit"><br/>
</form>


<?php

$filename = 'kadai2-2.txt';
//read txt file and put each line into array "line" if exists
if(is_file($filename)){
	$line = file($filename);

	foreach($line as $value){
		//remove \n
		$value = str_replace("\n", "", $value);
		//break a line into elements and put into array "piece"
		$piece = explode("<>", $value);
		//$piece[0]:$num, $piece[1]:$name, piece[2]:$comment, piece[3]:date
		echo $piece[0]." ".$piece[1]." ".$piece[2]." ".$piece[3]."<br/>";
	}
}

?>


</body>

</html>
