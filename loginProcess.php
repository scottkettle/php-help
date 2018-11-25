<?php
ini_set("session.session_save_path", "/home/unn_w16040109/sessionData");
session_start();
require_once('functions.php');
echo makePageStart();
echo makeHeaderAndNav();

$username = filter_has_var(INPUT_POST, 'username') ? $_POST['username']: null;
$username = trim($username);
$password = filter_has_var(INPUT_POST, 'password') ? $_POST['password']: null;
$password = trim($password);

if (empty($username) || empty($password)) {
	echo "<p> You need to provide both a username and password. Please try <a href='loginForm.php'>again</a>.</p>\n";

}

else{
	try{
		unset($_SESSION['username']);
		unset($_SESSION['logged-in']);

        $dbConn = getConnection();
        $sqlQuery = "SELECT passwordHash FROM nmc_users WHERE username = :username";
        $stmt = $dbConn->prepare($sqlQuery);
        $stmt->execute(array(':username' => $username));
        $user = $stmt->fetchObject();

        if ($user){
        	if (password_verify($password, $user->passwordHash)){
        		echo"<p> Logon has been a success!</p>\n";
        		echo"<p> As a user you have access to the following page : </p>\n";
        		echo"<a href='chooseRecordList.php'>Choose List Page</a>\n";

        		$_SESSION['logged-in'] = true;

        		$_SESSION['username'] = $username;

        	} else{
        		echo "<p>The username or Password you entered were incorrect. Please try <a href='loginForm.php'>again</a>.</p>\n";
        	}
        }else{
        	echo "<p>The username or Password you entered were incorrect. Please try <a href='loginForm.php'>again</a>.</p>\n";
        }

        } catch(expectation $e){
        	echo "Record not found: " . $e->getMessage();
        }


}



echo makeFooter();
echo makePageEnd();
?>

