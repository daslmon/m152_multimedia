<?php
    include 'upload.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <!-- upload file form -->
    <div class="container-sm">
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file[]" multiple>
            <button type="submit" name="upload">UPLOAD</button>
        </form>
    </div>

    <!-- build uploaded files -->
    <div class="container-sm">
        <form action="download.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <?php
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if(isset($_SESSION["thumbnails"])){
                        $fileCount = count($_SESSION["thumbnails"]);
                        for($i = 0; $i < $fileCount; $i++){
                            $path = $_SESSION["thumbnails"][$i];
                            echo <<<TOP
                            <div class="card" style="width: 18rem;">
                            <img src="$path" id="$i" class="card-img-top" alt="img">
                                <div class="card-body">
                                    <label for="position$i" class="form-label">Position</label>
                                    <select name="position$i" id="position">
TOP;
                                    //build and set position options
                                    for($j = 1; $j <= $fileCount; $j++){
                                        if(($i+1) == $j){
                                            echo "<option value=".$j." selected>$j</option>";
                                        }else{
                                            echo "<option value=".$j.">$j</option>";
                                        }
                                    }

                                    echo <<<BOTTOM
                                    </select>
                                    <label for="time$i" class="form-label">Time</label>
                                    <input type="number" name="time$i" value="10" min="0" max="100">
                                    <button type="submit" name="delete$i" class="btn btn-danger">DELETE</button>
                                </div>
                            </div>
BOTTOM;
                            }
                        }
                    ?>
            </div>
            <button type="submit" name="download">DOWNLOAD</button>
        </form>
    </div>
</body>
</html>