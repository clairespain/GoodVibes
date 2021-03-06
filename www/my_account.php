<?php
include('includes/db_connection.php');
include('includes/Login.php');
if (Login::isLoggedIn()) {
        $idusers = Login::isLoggedIn();
} else {
        die('Not Logged in');
}
// include('includes/Image.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $file = $_FILES['file'];
            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileError = $_FILES['file']['error'];
            $fileType = $_FILES['file']['type'];
                
            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));
        
            $allowed = array('jpg', 'jpeg', 'png');
        
            if (in_array($fileActualExt, $allowed)) {
                if ($fileError === 0) {
                    // I increased the size so that an image could get uploaded that was bigger than 50K
                    // See I don't know how to do math
                    if ($fileSize < 500000) {
                        $fileNameNew = uniqid('', true).".".$fileActualExt;
                        $fileDestination = 'uploads/'.$fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);
                        header("Location: my_account.php?uploadsuccess");
                        
                        //inserts the file path of my image from my directory into the database.
                        $InsertImage = DB::query('UPDATE goodvibes.users SET profileimg= :profileimg WHERE idusers =:idusers', array(':profileimg'=>$fileDestination, ':idusers'=>$idusers));
        
                    }else{
                        echo "Your file is too big!";
                    }
                }else{
                    echo "There was an error uploading your file!";
                }
            }else{
                echo "You cannot upload files of this type!";
            }
        }
        
?>

<h1>My Account</h1>
<!-- I changed the action to go to upload.php -->
<form action="my_account.php" method="post" enctype="multipart/form-data">
        Upload a profile image:
        <input type="file" name="file">
        <input type="submit" name="uploadprofileimg" value="Upload Image">
</form>