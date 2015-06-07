<?php

class CFileUpload {
    
    public function __construct() {
    }
    public function uploadFile($target_dir , $file) {
        $outputMsg = ""; 
        $target_file = $target_dir . basename($_FILES[$file]['name']);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES[$file]['tmp_name']);
            if($check !== false) {
                $outputMsg = "<div class='alert alert-success' role='alert'>File is an image - " . $check["mime"] . ".</div>";
                $uploadOk = 1;
            } else {
                $outputMsg = "<div class='alert alert-warning' role='alert'>File is not an image.</div>";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $outputMsg = "<div class='alert alert-danger' role='alert'>Sorry, file already exists.</div>";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES[$file]["size"] > 500000) {
            $outputMsg = "<div class='alert alert-danger' role='alert'>Sorry, your file is too large.</div>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $outputMsg = "<div class='alert alert-danger' role='alert'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $outputMsg = "<div class='alert alert-danger' role='alert'>Sorry, your file was not uploaded.</div>";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES[$file]["tmp_name"], $target_file)) {
                $outputMsg = "<div class='alert alert-success' role='alert'>The file ". basename( $_FILES[$file]["name"]). " has been uploaded.</div>";
            } else {
                $outputMsg = "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
            }
        }
        return $outputMsg; 
    }
}
