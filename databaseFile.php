<?php
//$dsn = 'mysql:host=localhost;dbname=join_me_travel';
//$username = 'root';
//$password = '';
//
//try {
//    $db = new PDO($dsn, $username, $password);
//} catch (PDOException $e) {
//    $error_message = $e->getMessage();
//    include('databaseError.php');
//    exit();
//}

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

            $sql = 'select email from user_table';
            $statement = self::$db->prepare($sql);
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

            $sql = 'select user_password from user_table where email = :email';
            $statement = self::$db->prepare($sql);
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
    //checking if both login and password exist in table
    public static function checkLogin($email, $password)
    {
        try
        {
            self::$db = self::getDB();

            $sql = 'select user_password from user_table where email = :email and password = :password';
            $statement = self::$db->prepare($sql);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
            $valResult = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $valResult;

    }

    public static function getUserInfo($email){
         try{
             self::$db = self::getDB();

             $sql = 'select * from user_table where email = :email';
             $statement = self::$db->prepare($sql);
             $statement->bindValue(':email', $email);
             $statement->execute();
             $userInfo = $statement->fetchAll();
             $statement->closeCursor();

         }catch
         (PDOException $e) {
             exit();
         }

         return $userInfo;
    }

    public static function updateUserInfo($email, $firstName, $occupation, $address, $summary){
        try{
            self::$db = self::getDB();

            $sql = 'UPDATE user_table SET user_firstname = "'.$firstName.'", occupation = "'.$occupation.'", address = "'.$address.'", summary = "'.$summary.'"   where email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function getImage($email){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT image_name FROM user_table WHERE email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $image = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $image;


    }

    public static function updateImageName($email, $filename){
        try
        {
            self::$db = self::getDB();

            $sql = 'UPDATE user_table SET image_name = "'.$filename.'" WHERE email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function insertGalleryImages($email, $filename, $filename_700, $filename_400){
        try
        {
            self::$db = self::getDB();

            $sql = 'INSERT INTO user_images (user_email, gallery_image, gallery_image_700, gallery_image_400) VALUES ("'.$email.'", "'.$filename.'","'.$filename_700.'", "'.$filename_400.'")';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function getGalleryImage($email){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT gallery_image_700 FROM user_images WHERE user_email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $image = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $image;

    }
    public static function getGalleryImage_400_700($email){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT gallery_image, gallery_image_700, gallery_image_400 FROM user_images WHERE user_email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $image = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $image;

    }

    public static function deleteGalleryImages($email, $filename){
        try
        {
            self::$db = self::getDB();

            $sql = 'DELETE FROM user_images WHERE gallery_image = "'.$filename.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }



    //insert information into user_table
    public static function insertUser($firstname, $lastname, $email, $password, $occupation, $address, $city, $country, $summary, $latitude, $longitude){
        try{
            self::$db = self::getDB();

            $query = 'Insert into user_table(date_created, date_modified, user_firstname, user_lastname, email, user_password, occupation, address, city, country, summary, latitude, longitude, active)
            values (:date_created, :date_modified, :firstName, :lastName, :email, :user_password, :occupation, :address, :city, :country, :summary, :latitude, :longitude, :active)';
            $statement = self::$db->prepare($query);
            $statement->bindValue(':date_created', date("Y-m-d H:i:s"));
            $statement->bindValue(':date_modified', date("Y-m-d H:i:s"));
            $statement->bindValue(':firstName', $firstname);
            $statement->bindValue(':lastName', $lastname);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':user_password', $password);
            $statement->bindValue(':occupation', $occupation);
            $statement->bindValue(':address', $address);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':country', $country);
            $statement->bindValue(':summary', $summary);
            $statement->bindValue(':latitude', $latitude);
            $statement->bindValue(':longitude', $longitude);
            $statement->bindValue(':active', 0);
            $statement->execute();
            $statement->closeCursor();
            $message="success";
        }catch
        (PDOException $e) {
            exit();
        }
        return $message;
    }

    //insert information into description table
    public static function insertDescription($email, $description, $destinationCountry, $destinationCity, $startDate, $endDate){
        try{
            self::$db = self::getDB();

            $query = 'Select user_id from user_table where email=:email';
            $statement = self::$db->prepare($query);
            $statement->bindValue(':email', $email);
            $statement->execute();
            $userIds = $statement->fetchAll();
            $statement->closeCursor();
            $userId="";
            foreach ($userIds as $key=>$value){
                $userId=$value;
            }


            $query = 'Insert into user_description (user_id, desc_date, description, destinationCountry, destinationCity, startDate, endDate)
              values(:userId, :date, :description, :destinationCountry, :destinationCity, :startDate, :endDate)';
            $statement = self::$db->prepare($query);
            $statement->bindValue(':userId', $userId[0]);
            $statement->bindValue(':date', date("Y-m-d H:i:s"));
            $statement->bindValue(':description', $description);
            $statement->bindValue(':destinationCountry', $destinationCountry);
            $statement->bindValue(':destinationCity', $destinationCity);
            $statement->bindValue(':startDate', $startDate);
            $statement->bindValue(':endDate', $endDate);
            $statement->execute();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }
    }

}
?>