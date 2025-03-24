<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GPHomeWork4</title>
</head>
<body>
    <div class="div1">
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Task 1</h1>
            <h2>Please, upload PNG, JPG or GIF only. <br> Their Size should be under 100mb !</h2>
            <input type="file" name="input1" accept=".png , .jpg , .jpeg , .gif" required>
            <br>
            <br>
            <br>
            <button name="button1">Submit</button>

            <?php
                if(isset($_POST['button1'])) {
                    if(isset($_FILES['input1'])) {
                        $FileName = $_FILES['input1']['name'];
                        $FileSize = $_FILES['input1']['size'];
                        $FileTmp = $_FILES['input1']['tmp_name'];
                        $FileExt = strtolower(pathinfo($FileName, PATHINFO_EXTENSION));
                        $Allowed = ['png', 'jgp', 'jpeg', 'gif'];

                        if(in_array($FileExt, $Allowed)) {
                            if($FileSize <= 100 * 1024 * 1024) {
                                if(!is_dir("Files")) {
                                    mkdir("Files");
                                }
                                move_uploaded_file($FileTmp, "Files/" . $FileName);
                                echo "<div class='div2'><p>File has been Uploaded !</p></div>";

                            }
                            else {
                                echo "<div class='div2'><p>File is too Large !</p></div>";
                            }
                        }
                        else {
                            echo "<div class='div2'><p>Invalid File Type !</p></div>";
                        }
                    }
                    else {
                        echo "<div class='div2'><p>Eror While Uploading... !</p></div>";
                    }
                }
            ?>
        </form>
    </div>
</body>
</html>