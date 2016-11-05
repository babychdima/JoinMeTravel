<?php
$dsn = 'mysql:host=localhost;dbname=join_me_travel';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('databaseError.php');
    exit();
}

class TravelDatabase
{
    private static $dsn = 'mysql:host=localhost;dbname=join_me_travel';
    private static $username = 'root';
    private static $password = '';
    private static $db;


    public static function getDB()
    {
        try {
            self::$db = new PDO(self::$dsn, self::$username, self::$password);
        } catch
        (PDOException $e) {

            exit();
        }
        return self::$db;
    }

    public static function getLogin()
    {
        try
        {
            self::$db = self::getDB();

            $queryInsurance = 'select email from user_table';
            $statement = self::$db->prepare($queryInsurance);
            $statement->execute();
            $email = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $email;

    }

    public static function getPassword($email)
    {
        try
        {
            self::$db = self::getDB();

            $queryInsurance = 'select user_password from user_table where email = :email';
            $statement = self::$db->prepare($queryInsurance);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $password = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $password;

    }
}
?>