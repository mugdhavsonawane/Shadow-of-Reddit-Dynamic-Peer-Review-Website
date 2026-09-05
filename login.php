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
<h1> Login </h1>
<a href="./index.php"><button type="button">Home</button></a>
<form method="post" action="./controller.php">
<div class="containerReg">

<input type="text" id="username" name="username" placeholder="Username" required>
<br> 
<br>
<input type="password" id="password" name="password" placeholder="Password" required>
<br>
<br>

<input type="submit" value="Login">
<br>

<br>

<?php
if( isset($_SESSION ['loginError'])) {
    echo $_SESSION ['loginError'];
}

unset($_SESSION ['loginError']);

?>



</div>
</form>

<footer class="site-footer">
    <p>&copy; 2026 Mugdha Sonawane.</p>
</footer>

</body>
</html>