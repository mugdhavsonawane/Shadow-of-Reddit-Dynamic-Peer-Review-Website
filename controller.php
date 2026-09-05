<?php
// This file contains a bridge between the view and the model and redirects back to the proper page
// with after processing whatever form this code absorbs. This is the C in MVC, the Controller.
//
// Author: Mugdha Sonawane
//  
session_start (); // Not needed until a future iteration

// logout
if (isset($_POST['logout'])) {

    // Remove everything stored in the session
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Send the user back to the home page
    header("Location: index.php");
    exit();
}
// add three another if statement that send messages to your DatabaseAdapter

require_once './DatabaseAdaptor.php';

$theDBA = new DatabaseAdaptor();

if (isset ( $_GET['todo'] ) && $_GET['todo'] === 'getQuotes') {
    $arr = $theDBA->getAllQuotations();
    unset($_GET ['todo']);

    // we're sending this back to the view.php file 
    echo getQuotesAsHTML ( $arr );
}



if (isset($_POST['update'])) {

    // Voting/deleting requires login
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $clickedName = $_POST['update'];
    $ID = (int) $_POST['id'];

    if ($clickedName === "increase") {

        $theDBA->raiseRating($ID);

    } elseif ($clickedName === "decrease") {

        $theDBA->decreaseRating($ID);

    } elseif ($clickedName === "delete") {

        // The database will delete only if this
        // logged-in user is actually the author.
        $theDBA->deleteQuote(
            $ID,
            $_SESSION['username']
        );
    }

    header("Location: index.php");
    exit();
}

// login
if (isset ( $_POST['username'] ) && isset ( $_POST['password'] )) {
    if ($theDBA->verifyCredentials($_POST['username'], $_POST['password'])) {
    
            // Store Session Data
        session_regenerate_id(true);
        $_SESSION['username'] = $_POST['username'];
    
        header("Location: index.php");
        exit();
    } else {
    
        $_SESSION['loginError'] = 'Invalid Username or Password';
    
        header("Location: login.php");
        exit();
    }
}

// register
if (isset ( $_POST['newUsername'] ) && isset ( $_POST['newPassword'] )) {
    if ($theDBA->register($_POST['newUsername'], $_POST['newPassword'])) {
        
        // Store Session Data
        session_regenerate_id(true);
        $_SESSION['username'] = $_POST['newUsername'];
        
        header("Location: index.php");
        exit();
        
    } else {
        
        $_SESSION['accountNameTaken'] = 'Account name taken';
        
        header("Location: register.php");
        exit();
    }
}

if (isset($_POST['quote'])) {

    // Must be logged in to create a quote
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $quote = trim($_POST['quote']);

    if ($quote !== '') {

        // The author comes from the session,
        // NOT from the form.
        $theDBA->addQuote(
            $quote,
            $_SESSION['username']
        );
    }

    header("Location: index.php");
    exit();
}

function getQuotesAsHTML($arr) {

    $result = '';

    $loggedIn = isset($_SESSION['username']);

    foreach ($arr as $quote) {

        $quoteText = htmlspecialchars($quote['quote']);
        $author = htmlspecialchars($quote['author']);
        $rating = (int) $quote['rating'];
        $id = (int) $quote['id'];

        $result .= '<div class="container">';

        $result .= '"' . $quoteText . '"<br>';

        $result .= '<p class="author">
                        -- ' . $author . '
                    </p>';

        /*
         * Logged-out visitors can see the rating,
         * but cannot vote or delete.
         */
        if (!$loggedIn) {

            $result .= '<p>Rating: ' . $rating . '</p>';

        } else {

            /*
             * Logged-in users can vote.
             */
            $result .= '
                <form method="post" action="controller.php">

                    <input
                        type="hidden"
                        name="id"
                        value="' . $id . '"
                    >

                    <button
                        name="update"
                        value="increase"
                    >
                        +
                    </button>

                    <span class="rating">
                        ' . $rating . '
                    </span>

                    <button
                        name="update"
                        value="decrease"
                    >
                        -
                    </button>
            ';

            /*
             * Only the person who created this quote
             * gets a Delete button.
             */
            if ($_SESSION['username'] === $quote['author']) {

                $result .= '
                    <button
                        name="update"
                        value="delete"
                    >
                        Delete
                    </button>
                ';
            }

            $result .= '</form>';
        }

        $result .= '</div>';
    }

    return $result;
}
?>