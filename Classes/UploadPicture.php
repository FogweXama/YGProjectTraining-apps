<?php
    class UploadPicture
    {
        private $target_file;
        public $image,$source;

        public static function getfile(){
            while($row = mysql_fetch_row($result)) {
                echo "<tr>";
                echo "<td><img src='uploads/$row[6].jpg' height='150px' width='300px'></td>";
                echo "</tr>\n";
            }
        }
        public static function display($psource=null)
        {
              if($psource){
                //Process the image that is uploaded by the user
                $target_dir = "Image/DPUploads";
                $target_file = $target_dir . basename($_FILES[$psource]["name"]);
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                return $target_file;
              }
        }
        public static function upload($target_file=null){
            if($target_file){
                if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file)) {
                    echo "The file ". basename( $_FILES["imageUpload"]["name"]). " has been uploaded.";
                } 
                else {
                    echo "Sorry, there was an error uploading your file.";
                }

                $image=basename( $_FILES["imageUpload"]["name"],".jpg"); // used to store the filename in a variable

                return $image;
            }
            else{
                echo 'unable to grasp image';
            }
        }
        public static function Insert(){
            //storind the data in your database
            $query= "INSERT INTO items VALUES ('$id','$title','$description','$price','$value','$contact','$image')";
            mysql_query($query);
        
            require('heading.php');
            echo "Your add has been submited, you will be redirected to your account page in 3 seconds....";
            header( "Refresh:3; url=account.php", true, 303);
        }
    }

?>