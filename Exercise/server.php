<?php
session_start();

// variable declaration
$username = "";
$email    = "";
$errors = array(); 
$_SESSION['success'] = "";

// connect to database
//$db = mysqli_connect('localhost', 'root', 'Qwerty19', 'registration');
$db = mysqli_connect('mywebclass.cfe6114gdoyl.us-east-1.rds.amazonaws.com', 'david', 'Qwerty19', 'registration');
// REGISTER USER
if (isset($_POST['reg_user'])) {
	// receive all input values from the form
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
	$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { array_push($errors, "Username is required"); }
	if (empty($email)) { array_push($errors, "Email is required"); }
	if (empty($password_1)) { array_push($errors, "Password is required"); }
	

	$otherNames = mysqli_query($db, "SELECT * from exerciseusers ");
	while($otherNamesarray = mysqli_fetch_array($otherNames))
	{
	$other = $otherNamesarray['username'];
	if (strcasecmp($username, $other) == 0) { array_push($errors, "User Name is already taken"); }
	}

	





	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = sha1($password_1);//encrypt the password before saving in the database
		$query = "INSERT INTO exerciseusers (username, email, password) 
				  VALUES('$username', '$email', '$password')";
		mysqli_query($db, $query);

		$_SESSION['username'] = $username;
		$_SESSION['success'] = "You are now logged in";
		header('location: index.php');
	}

}

// ... 
// ... 

// LOGIN USER
if (isset($_POST['login_user'])) {
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = mysqli_real_escape_string($db, $_POST['password']);

	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	if (count($errors) == 0) {
		$password = sha1($password);
		$query = "SELECT * FROM exerciseusers WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) {
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: index.php');
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}


// Submit Data
if (isset($_POST['info'])) {
	$info = $_POST['info'];
	$original = mysqli_query($db, "SELECT * from exerciseusers ");
	while($originalarray = mysqli_fetch_array($original))
	{
	//echo $originalarray['username'];
	//$test = "hi";
		if (strcasecmp($_SESSION['username'], $originalarray['username']) == 0)
		{
			$info = $info . ' // ' . $originalarray['userdata'];
			//echo $info;
			$user = $originalarray['username'];
			$submit ="UPDATE exerciseusers SET userdata= '$info' WHERE username = '$user'";
			 //$originalarray['data'] = ;
			 mysqli_query($db, $submit);
			 //echo $user;
		}
	}
}


//Get Data
if (isset($_POST['others'])) {
	$others = $_POST['others'];
	$detector = 0;
	//echo($others);
	$original = mysqli_query($db, "SELECT * from exerciseusers ");
	while($originalarray = mysqli_fetch_array($original))
	{
	//echo $originalarray['username'];
	//$test = "hi";
		if (strcasecmp($others, $originalarray['username']) == 0)
		{
			$detector = 1;
			//echo $info;
			$user = $originalarray['username'];
			$request ="SELECT userdata FROM exerciseusers Where username ='$user'";
			 //$originalarray['data'] = ;
			$return = mysqli_query($db, $request);
			 //echo(my$return);
			 echo $originalarray['username'];
			 echo"//";
			while( $row = mysqli_fetch_assoc( $return ) ){
				echo $row['userdata'];
			  }
		}
		
	}
	if ($detector == 0)
	{
		echo('User Not Found. Please try again');
	}
}






//Get My Data
if (isset($_POST['me'])) {
	$others = $_SESSION['username'];
	$detector = 0;
	//echo($others);
	$original = mysqli_query($db, "SELECT * from exerciseusers ");
	while($originalarray = mysqli_fetch_array($original))
	{
	//echo $originalarray['username'];
	//$test = "hi";
		if (strcasecmp($others, $originalarray['username']) == 0)
		{
			$detector = 1;
			//echo $info;
			$user = $originalarray['username'];
			$request ="SELECT userdata FROM exerciseusers Where username ='$user'";
			 //$originalarray['data'] = ;
			$return = mysqli_query($db, $request);
			 //echo(my$return);
			 echo $originalarray['username'];
			 echo"//";
			while( $row = mysqli_fetch_assoc( $return ) ){
				echo $row['userdata'];
			  }
		}
		
	}
	if ($detector == 0)
	{
		echo('User Not Found. Please try again');
	}
}







//final
if (isset($_POST['suggestions'])) {
	//$info = $_POST['info'];
	$array = array();
	$original = mysqli_query($db, "SELECT * from exerciseusers ");
	while($originalarray = mysqli_fetch_array($original))
	{
	//echo $originalarray['username'];
	//$test = "hi";


		array_push($array, $originalarray['username']);
		//echo "hi";
		//echo($array);
	}
	$stringofarray = $array[0];
	for($i = 1; $i<count($array); $i++)
	{
	$stringofarray = $stringofarray . "  " . $array[$i];
	}
	print($stringofarray);
}
?>

