<?php


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

    public static function getUserIdByEmail($email){
        try{
            self::$db = self::getDB();

            $sql = 'select user_id from user_table where email = "'.$email.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $userId= $statement->fetchAll();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

        return $userId;


    }

    public static function getUserInfo($id){
         try{
             self::$db = self::getDB();

             $sql = 'select * from user_table where user_id = :id';
             $statement = self::$db->prepare($sql);
             $statement->bindValue(':id', $id);
             $statement->execute();
             $userInfo = $statement->fetchAll();
             $statement->closeCursor();

         }catch
         (PDOException $e) {
             exit();
         }

         return $userInfo;
    }

    public static function updateUserInfo($id, $firstName, $lastName, $occupation, $address, $summary, $lat, $long){
        try{
            self::$db = self::getDB();

            $sql = 'UPDATE user_table SET user_firstname = "'.$firstName.'", user_lastname = "'.$lastName.'", occupation = "'.$occupation.'", address = "'.$address.'", summary = "'.$summary.'" , latitude = "'.$lat.'", longitude = "'.$long.'"   where user_id = "'.$id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function updateDescInfo($id, $desc, $dest_country, $dest_city, $st_date, $end_date){
        try{
            self::$db = self::getDB();

            $sql = 'UPDATE user_description SET description = "'.$desc.'", destinationCountry = "'.$dest_country.'", destinationCity = "'.$dest_city.'", startDate = "'.$st_date.'", endDate = "'.$end_date.'"   where user_id = "'.$id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function getImage($id){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT image_name FROM user_table WHERE user_id = "'.$id.'"';
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

    public static function updateImageName($id, $filename){
        try
        {
            self::$db = self::getDB();

            $sql = 'UPDATE user_table SET image_name = "'.$filename.'" WHERE user_id = "'.$id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function insertGalleryImages($id, $filename, $filename_700, $filename_400){
        try
        {
            self::$db = self::getDB();

            $sql = 'INSERT INTO user_images (user_id, gallery_image, gallery_image_700, gallery_image_400) VALUES ("'.$id.'", "'.$filename.'","'.$filename_700.'", "'.$filename_400.'")';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }

    public static function getGalleryImage($id){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT gallery_image_700 FROM user_images WHERE user_id = "'.$id.'"';
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
    public static function getGalleryImage_400_700($id){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT gallery_image, gallery_image_700, gallery_image_400 FROM user_images WHERE user_id = "'.$id.'"';
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

    public static function deleteGalleryImages($id, $filename){
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

    public static function getUserDescription(){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT * FROM user_description';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $dates = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $dates;

    }


    public static function getUserDescriptionById($user_id){
        try
        {
            self::$db = self::getDB();

            $sql = 'SELECT * FROM user_description where user_id = "'.$user_id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $dates = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $dates;

    }


    public static function getUserInfoById($id){
        try{
            self::$db = self::getDB();

            $sql = 'select * from user_table where user_id = "'.$id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $userInfo = $statement->fetchAll();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

        return $userInfo;
    }

    public static function getUserId(){
        try{
            self::$db = self::getDB();

            $sql = 'select user_id from user_table';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $userId= $statement->fetchAll();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

        return $userId;

    }




    public static function showTravellers(){
        $var=2;
        try{
            self::$db = self::getDB();
            $sql = "SELECT * FROM user_description join user_table on user_description.user_id=user_table.user_id";
            $statement = self::$db->prepare($sql);
            $statement->execute();

            $locationsAll = $statement->fetchAll();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

        return $locationsAll;

    }

    public static function searchTravellers($searchString){

        try{
            self::$db = self::getDB();
            $sqlSearch = "SELECT  * FROM user_description join user_table on user_description.user_id=user_table.user_id
            IN(SELECT distinct destinationCity, distinct destinationCountry from user_description where  
            destinationCity='".$searchString."' OR destinationCountry='".$searchString."')";
            $statementSearch = self::$db->prepare($sqlSearch);
            $statementSearch->execute();
            $locationsSearch = $statementSearch->fetchAll();
            $statementSearch->closeCursor();
        }
        catch
        (PDOException $e) {
            exit();
        }

        return $locationsSearch;

    }

//add new message to database
    public static function insertMessage($date, $msg_from, $msg_to, $msg){
        try
        {
            self::$db = self::getDB();

            $sql = 'INSERT INTO user_messages (date, message_from, message_to, message_content) VALUES ("'.$date.'", "'.$msg_from.'","'.$msg_to.'", "'.$msg.'")';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }
//get messages ordered by date
    public static function getMessages($id){
        try{
            self::$db = self::getDB();

            $sql = 'select user_table.user_firstname, user_table.user_lastname, user_messages.date, user_messages.replied, user_messages.message_id, user_messages.message_from,user_messages.message_to, user_messages.message_content from user_messages 
              INNER JOIN user_table ON user_messages.message_from=user_table.user_id where message_to ="'.$id.'" order by user_messages.date DESC';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $msg= $statement->fetchAll();
            $statement->closeCursor();

        }catch
        (PDOException $e) {
            exit();
        }

        return $msg;

    }

    public static function updateRepliedStatus($msg_id){
        try
        {
            self::$db = self::getDB();

            $sql = 'UPDATE user_messages SET replied = 1 WHERE message_id = "'.$msg_id.'"';
            $statement = self::$db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }catch
        (PDOException $e) {
            exit();
        }

    }

}

?>