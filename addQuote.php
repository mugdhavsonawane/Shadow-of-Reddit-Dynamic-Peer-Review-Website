<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Quote</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

<h1>Add a Quote</h1>
<a href="./index.php"><button type="button">Home</button></a>
<h3>
    Posting as:
    <?php echo htmlspecialchars($_SESSION['username']); ?>
</h3>

<form method="post" action="controller.php">
    <div class="containerAdd">

        <textarea
            class="quote"
            id="quoteIn"
            name="quote"
            placeholder="Enter new quote"
            required
        ></textarea>

        <br>

        <button type="submit">Add Quote</button>

    </div>
</form>

<footer class="site-footer">
    <p>&copy; 2026 Mugdha Sonawane.</p>
</footer>
    
</body>
</html>