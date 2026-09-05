<?php
session_start();
?>
<!-- Author: Mugdha Sonawane -->

<!DOCTYPE html>
<html>
<head>
<title>Quotation Service</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
<h1> Register </h1>
<a href="./index.php"><button type="button">Home</button></a>
<form method="post" action="./controller.php">
<div class="containerReg">

<input type="text" id="username" name="newUsername" placeholder="Username" required>
<br> 
<br>
<input type="password" id="password" name="newPassword" placeholder="Password" required>
<br>
<br>

<input type="submit" value="Register">

<br>

<br>

<?php
if( isset($_SESSION ['accountNameTaken'])) {
      echo $_SESSION ['accountNameTaken'];
}

unset($_SESSION ['accountNameTaken']);

?>



</div>
</form>

<footer class="site-footer">
      <p>&copy; 2026 Mugdha Sonawane.</p>
</footer>


</body>
</html>