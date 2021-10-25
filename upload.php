<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    if(!isset($_SESSION["files"])){
        $_SESSION["files"] = array();
    }
    if(!isset($_SESSION["thumbnails"])){
        $_SESSION["thumbnails"] = array();
    }
    if(!isset($_SESSION["durations"])){
        $_SESSION["durations"] = array();
    }
    if(!isset($_SESSION["position"])){
        $_SESSION["position"] = array();
    }
}

if(isset($_POST['upload'])){
    $fileCount = count($_FILES['file']['name']);
    for($i = 0; $i < $fileCount; $i++){
        $fileName  = $_FILES['file']['name'][$i];
        $fileTmp   = $_FILES['file']['tmp_name'][$i];
        $fileSize  = $_FILES['file']['size'][$i];
        $fileError = $_FILES['file']['error'][$i];
        $fileType  = $_FILES['file']['type'][$i];

        $fileExt = strtolower(explode('.', $fileName)[1]);
        $allowedExt = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        if(in_array($fileExt, $allowedExt)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    //creat folder
                    if(!isset($_SESSION["folder"]) || !file_exists("uploads/".$_SESSION["folder"])){
                        $_SESSION["folder"] = uniqid('', true);
                        mkdir("uploads/".$_SESSION["folder"], 0777, true);
                        mkdir("uploads/".$_SESSION["folder"]."/thumbnails", 0777, true);
                    }
                    //set file path
                    $fileDestination = "uploads/".$_SESSION["folder"]."/".$fileName;
                    //set files
                    array_push($_SESSION["files"], $fileDestination);
                    //upload files
                    move_uploaded_file($fileTmp, $fileDestination);
                    createThumbnail($fileExt, $fileDestination, $fileName);
                    //set thumbnails
                    array_push($_SESSION["thumbnails"], "uploads/".$_SESSION["folder"]."/thumbnails/".$fileName);
                    //back to index
                    header("Location: index.php?uploadsuccess");
                }else{
                    echo "Your file is to big!";
                }
            }else{
                echo "There was an error uploading your file!";
            }
        }else{
            echo "You cannot upload file of this type!";
        }
    }
}

function createThumbnail($fileExt, $fileDestination, $fileName){
    $img = "";
    echo $fileExt;
    if($fileExt == "jpg" || $fileExt == "jpeg"){
        $img = imageCreateFromJPEG($fileDestination);
    }else if($fileExt == "png"){
        $img = imageCreateFromPNG($fileDestination);
    }else if($fileExt == "webp"){
        $img = imageCreateFromWebp($fileDestination);
    }else if($fileExt == "gif"){
        $img = imageCreateFromGIF($fileDestination);
    }
    $imgWitdh = imageSX($img);
    $imgHeight = imageSY($img);

    $size = 200;
    $x = ($imgWitdh-$size)/2;
    $y = ($imgHeight-$size)/2;

    $thumbnail = imagecrop($img, ['x' => $x, 'y' => $y, 'width' => $size, 'height' => $size]);
    imageJpeg($thumbnail, "uploads/".$_SESSION["folder"]."/thumbnails/".$fileName, 70);

    imageDestroy($img);
    imageDestroy($thumbnail);
}
?>