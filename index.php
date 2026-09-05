<?php
session_start();
?>
<!-- 
File name quotes.php 
    
Author: Mugdha Sonawane
-->

<!DOCTYPE html>
<html>
<head>
    <title>Shadow of Reddit: A Dynamic Peer Review Website</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body onload="showQuotes()">

<h1>Shadow of <span style="color: red;">reddit</span></h1> 
<h3>A Dynamic Peer Review Website</h3>

<?php

if (isset($_SESSION['username'])) {

    echo '<h2>Hello <span class="username">' . htmlspecialchars($_SESSION['username']) . '</span></h2>';

    echo '<a href="./addQuote.php"><button>Add Quote</button></a>';

    echo '
        <form method="post" action="controller.php" style="display:inline;">
            <button type="submit" name="logout" value="1">
                Logout
            </button>
        </form>
    ';

} else {

    echo '<a href="./register.php"><button>Register</button></a>';

    echo '<a href="./login.php"><button>Login</button></a>';
}

echo '<hr>';

?>

<div id="quotes"></div>

<script>

function showQuotes() {
    var element = document.getElementById("quotes");
    var obj = new XMLHttpRequest();

    obj.open("GET", "controller.php?todo=getQuotes");
    obj.send();

    obj.onreadystatechange = function() {
        if (obj.readyState == 4 && obj.status == 200) {
            element.innerHTML = obj.responseText;
        }
    };
}

</script>
<footer class="site-footer">
    <p>&copy; 2026 Mugdha Sonawane.</p>
</footer>
</body>
</html>