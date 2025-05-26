<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['UserLoggedIn'])) {
    header('Location: login.php');
    exit();
}

$MessageStatus = '';
$MessageType = '';

$GenresArray = [];
try {
    $StatementGenres = $DatabaseConnection->query("SELECT id, name FROM genres ORDER BY name ASC");
    $GenresArray = $StatementGenres->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TitleInput = trim($_POST['TitleInput']);
    $ArtistInput = trim($_POST['ArtistInput']);
    $AlbumInput = trim($_POST['AlbumInput']);
    $GenreIdInput = $_POST['GenreIdInput'];
    $UserId = $_SESSION['UserId'];

    $TargetDirectory = "uploads/";
    $OriginalFileName = basename($_FILES["MusicFileInput"]["name"]);
    $FileType = strtolower(pathinfo($OriginalFileName, PATHINFO_EXTENSION));
    $UniqueFileName = uniqid('music_', true) . '.' . $FileType;
    $TargetFile = $TargetDirectory . $UniqueFileName;

    $UploadOk = 1;

    $AllowedFileTypes = ["mp3", "wav", "aac", "ogg", "flac"];
    if (!in_array($FileType, $AllowedFileTypes)) {
        $MessageStatus = "Sorry, only MP3, WAV, AAC, OGG, & FLAC files are allowed.";
        $MessageType = 'ErrorMessageClass';
        $UploadOk = 0;
    }

    if ($_FILES["MusicFileInput"]["size"] > 50000000) { 
        $MessageStatus = "Sorry, your file is too large (max 50MB).";
        $MessageType = 'ErrorMessageClass';
        $UploadOk = 0;
    }

    if ($UploadOk == 0) {
    } else {
        if (move_uploaded_file($_FILES["MusicFileInput"]["tmp_name"], $TargetFile)) {
            try {
                $StatementUpload = $DatabaseConnection->prepare("INSERT INTO songs (user_id, title, artist, album, genre_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
                $StatementUpload->execute([$UserId, $TitleInput, $ArtistInput, $AlbumInput, $GenreIdInput, $TargetFile]);
                $MessageStatus = 'Music uploaded successfully!';
                $MessageType = 'SuccessMessageClass';
            } catch (PDOException $ExceptionObject) {
                $MessageStatus = 'Error saving to database: ' . $ExceptionObject->getMessage();
                $MessageType = 'ErrorMessageClass';
                if (file_exists($TargetFile)) {
                    unlink($TargetFile);
                }
            }
        } else {
            $MessageStatus = "Sorry, there was an error uploading your file. Error code: " . $_FILES["MusicFileInput"]["error"];
            $MessageType = 'ErrorMessageClass';
            // You might want to log $_FILES["MusicFileInput"]["error"] for debugging
            // UPLOAD_ERR_INI_SIZE (1) - The uploaded file exceeds the upload_max_filesize directive in php.ini.
            // UPLOAD_ERR_FORM_SIZE (2) - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
            // UPLOAD_ERR_PARTIAL (3) - The uploaded file was only partially uploaded.
            // UPLOAD_ERR_NO_FILE (4) - No file was uploaded.
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Music - SocialMusic</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="HeaderClass">
        <div class="ContainerClass HeaderContentClass">
            <a href="index.php" class="LogoClass">SocialMusic</a>
            <nav class="NavigationClass">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="upload.php">Upload Music</a></li>
                    <li><a href="my_music.php">My Music</a></li>
                    <li><a href="playlists.php">Playlists</a></li>
                    <li><a href="all_music.php">Browse All Music</a></li>
                    <?php if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin']): ?>
                        <li><a href="admin_panel.php">Admin Panel</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['Username']); ?>)</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="MainContentClass">
        <div class="CardClass">
            <h2>Upload New Music</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="FormGroupClass">
                    <label for="TitleInput">Title:</label>
                    <input type="text" id="TitleInput" name="TitleInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="ArtistInput">Artist:</label>
                    <input type="text" id="ArtistInput" name="ArtistInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="AlbumInput">Album (Optional):</label>
                    <input type="text" id="AlbumInput" name="AlbumInput">
                </div>
                <div class="FormGroupClass">
                    <label for="GenreIdInput">Genre:</label>
                    <select id="GenreIdInput" name="GenreIdInput">
                        <option value="">Select a Genre</option>
                        <?php foreach ($GenresArray as $GenreItem): ?>
                            <option value="<?php echo htmlspecialchars($GenreItem['id']); ?>">
                                <?php echo htmlspecialchars($GenreItem['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="FormGroupClass">
                    <label for="MusicFileInput">Music File (MP3, WAV, AAC, OGG, FLAC - Max 50MB):</label>
                    <input type="file" id="MusicFileInput" name="MusicFileInput" accept=".mp3,.wav,.aac,.ogg,.flac" required>
                </div>
                <button type="submit" class="ButtonClass">Upload Music</button>
            </form>
        </div>
    </main>

    <footer class="FooterClass">
        <div class="ContainerClass">
            <p>&copy; <?php echo date("Y"); ?> SocialMusic. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>