<?php

class DatabaseAdaptor {

    private $DB;

    public function __construct() {

        $config = require dirname(__DIR__) . '/shadow-of-reddit-config.php';

        $dataBase =
            'pgsql:host=' . $config['host']
            . ';port=' . $config['port']
            . ';dbname=' . $config['database'];

        try {

            $this->DB = new PDO(
                $dataBase,
                $config['user'],
                $config['password']
            );

            $this->DB->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );


        } catch (PDOException $e) {

            throw new RuntimeException(
                'Error establishing database connection.',
                0,
                $e
            );
        }
    }


    public function getAllQuotations() {

        $stmt = $this->DB->prepare(
            "SELECT * FROM quotations
             ORDER BY rating DESC;"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function getAllUsers() {

        $stmt = $this->DB->prepare(
            "SELECT * FROM users;"
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function addQuote($quote, $author) {

        $stmt = $this->DB->prepare(
            "INSERT INTO quotations
            (added, quote, author, rating, flagged)
            VALUES
            (CURRENT_TIMESTAMP, :quote, :author, 0, FALSE)"
        );

        $stmt->execute([
            ':quote' => $quote,
            ':author' => $author
        ]);
    }


    public function addUser($accountname, $psw) {

        $stmt = $this->DB->prepare(
            "INSERT INTO users
            (username, password)
            VALUES
            (:username, :password)"
        );

        $stmt->execute([
            ':username' => $accountname,
            ':password' => password_hash(
                $psw,
                PASSWORD_DEFAULT
            )
        ]);
    }


    public function verifyCredentials($accountName, $psw) {

        $stmt = $this->DB->prepare(
            "SELECT password
             FROM users
             WHERE username = :username"
        );

        $stmt->execute([
            ':username' => $accountName
        ]);

        $user = $stmt->fetch();

        return $user !== false
            && password_verify(
                $psw,
                $user['password']
            );
    }


    public function raiseRating($ID) {

        $stmt = $this->DB->prepare(
            "UPDATE quotations
             SET rating = rating + 1
             WHERE id = :id"
        );

        $stmt->execute([
            ':id' => (int) $ID
        ]);
    }


    public function decreaseRating($ID) {

        $stmt = $this->DB->prepare(
            "UPDATE quotations
             SET rating = rating - 1
             WHERE id = :id"
        );

        $stmt->execute([
            ':id' => (int) $ID
        ]);
    }


    public function deleteQuote($ID, $author) {

        $stmt = $this->DB->prepare(
            "DELETE FROM quotations
             WHERE id = :id
             AND author = :author"
        );

        $stmt->execute([
            ':id' => (int) $ID,
            ':author' => $author
        ]);
    }


    public function register($username, $password) {

        $stmt = $this->DB->prepare(
            "SELECT 1
             FROM users
             WHERE username = :username"
        );

        $stmt->execute([
            ':username' => $username
        ]);

        if ($stmt->fetchColumn() !== false) {
            return false;
        }

        $this->addUser(
            $username,
            $password
        );

        return true;
    }
}

?>