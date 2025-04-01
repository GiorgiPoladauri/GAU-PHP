<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload System</title>
</head>
<body>
    <h1>Upload your file</h1>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file">Choose file to upload:</label>
        <input type="file" name="file" id="file" required>
        <br><br>
        <input type="submit" value="Upload File">
    </form>

    <h2>Uploaded Files:</h2>
    <?php
        $dir = 'uploads/';
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo '<a href="'.$dir.$file.'" target="_blank">'.$file.'</a><br>';
                }
            }
        }
    ?>
</body>
</html>
