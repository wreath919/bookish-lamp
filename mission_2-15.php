<?php
//connect to database(mission 2-7)
$dsn='DBname';
$user='username';
$password='password';
$pdo= new PDO($dsn,$user,$password);

/*
//delete table
$sql="DROP TABLE tbtest"
$stmt=$pdo->query($sql);
*/

//create a table(mission 2-8)
$sql="CREATE TABLE tbtest4"
."("
."id INT primary key auto_increment,"
."name char(32),"
."comment TEXT,"
."date DATETIME,"
."password char(32)"
.");";
$stmt = $pdo->query($sql);

/*
echo "<hr>";
//check if target table is in table list(mission 2-9)
$sql='SHOW TABLES';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[0];
	echo '<br>';
}
echo "<hr>";
*/
/*
//check if table contains right contents(mission 2-10)
$sql='SHOW CREATE TABLE tbtest4';
$result=$pdo->query($sql);
foreach($result as $row){
	print_r($row);
}
echo "<hr>";
*/

//when form is used
if(!empty($_GET['name']) || !empty($_GET['comment'])){
	$name = $_GET['name'];
	$comment = $_GET['comment'];
	$hidden = $_GET['hidden'];
	$password = $_GET['password'];

	if(empty($name)){$name = "Joe Doe";}
	if(empty($comment)){echo "<FONT COLOR=\"RED\">Comment is not filled in.\n</FONT>";
	}else if(empty($password)){echo "<FONT COLOR=\"RED\">Password is not set.\n</FONT>";
	}else{

		//when hidden empty = adding mode, not empty = editing mode.
		//$hidden = target line number for editing	
		if(empty($hidden)){
	
			//insert info to the table (mission 2-11)
			$sql =$pdo->prepare("INSERT INTO tbtest4 (name, comment, date, password) VALUES (:name,:comment,cast(now() as datetime),:password)");


			//$sql->bindValue(':id',$num,PDO::PARAM_INT);
			$sql->bindParam(':name',$name,PDO::PARAM_STR);
			$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
			$sql->bindParam(':password',$password,PDO::PARAM_STR);

			$sql->execute();
			echo "Message is successfully added.";


		}else{
			
			//edit with target row with new name, comment, date & password sent in form
			$sql = $pdo->prepare('update tbtest4 set name = :nm, comment = :com, date = now(), password = :pass where id = :id');
			$sql->bindValue(':id', $hidden, PDO::PARAM_INT);
			$sql->bindPARAM(':nm', $name, PDO::PARAM_STR);
			$sql->bindPARAM(':com', $comment,PDO::PARAM_STR);
			$sql->bindPARAM(':pass', $password, PDO::PARAM_STR);
			$sql->execute();
			echo "Message is successfully editted.";

		}
	}
}


//delete
if(!empty($_POST['delete'])){
	$delete = $_POST['delete'];
	
	$stmt = "SELECT * from tbtest4 where id = $delete";
	$result = $pdo->query($stmt)->fetch(PDO::FETCH_ASSOC);
	if($result['password'] == $_POST['password']){
		if(!$result){
			echo "<font color =\"red\">The number does not exist.</font>"; 
		}else{

			$sql = $pdo->prepare('update tbtest4 set name= NULL, comment = "This message has been deleted.", date= NULL, password = NULL WHERE id = :delete_id');
			$sql->bindVALUE(':delete_id', $delete, PDO::PARAM_INT);
			$sql->execute();

			echo "Comment number $delete is deleted.\n";
		}
	}else{
		echo "<font color=\"red\">Password does not match.</font>";
	}
}

//when edit form is used
if(!empty($_POST['edit'])){
	$edit = $_POST['edit'];

	$sql = "SELECT * from tbtest4 where id = $edit";
	$row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
	
	if($row['password'] == $_POST['password']){
		if(!$row){
			echo "<font color =\"red\">The number does not exist.</font>";
		}else{
			$ename = $row['name'];
			$ecomment = $row['comment'];
		}
	}else{
		echo "<font color=\"red\">Password does not match.</font>";
	}
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

<form action = "mission_2-15.php" method = "get">
<font size = "3">Name:<br/></font>
<input type = "text" name = "name" 
	value = "<?php if(isset($ename))echo $ename; ?>"><br/>
<font size = "3">Comment:<br/></font>
<textarea name = "comment" rows = "4" cols = "40">
<?php if(isset($ecomment))echo $ecomment;?></textarea>
<br/>
<!_if editing hidden tag is 1_>
<input type = "hidden" name = "hidden" 
	value = "<?php if(isset($ename))echo $edit ?>">
<font size = "3">Password:<br></font>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Submit">
</form>



<!_make a delete form_>

<form action = "mission_2-15.php" method = "post" 
	>
<font size = "3">Delete Number:<br></font>
<input type = "text" name = "delete"><br/>
<font size = "3">Password:<br></font>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Delete"><br/>
</form>


<!_make a form for editing_>

<form action = "mission_2-15.php" method = "post">
<font size = "3">Edit Number:<br></font>
<input type = "text" name = "edit"><br/>
<font size = "3">Password:<br></font>
<input type = "password" name = "password"><br>
<input type = "submit" value = "Edit"><br/>
</form>

<font size = "3">
<?php

//show inserted date with "select"(mission 2-12)
$sql='SELECT * FROM tbtest4';
$results = $pdo->query($sql);
foreach ($results as $row){
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo nl2br($row['comment']).' ';
	echo $row['date'].'<br>';
}
echo "<hr>";


?>
</font>

</body>

</html>
