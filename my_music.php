<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['UserLoggedIn'])) {
    header('Location: login.php');
    exit();
}

$UserId = $_SESSION['UserId'];
$UserSongs = [];
$MessageStatus = '';
$MessageType = '';

// Handle song deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $SongIdToDelete = $_GET['id'];
    try {
        // First, get the file path to delete the actual file
        $StatementFilePath = $DatabaseConnection->prepare("SELECT file_path FROM songs WHERE id = ? AND user_id = ?");
        $StatementFilePath->execute([$SongIdToDelete, $UserId]);
        $SongToDelete = $StatementFilePath->fetch(PDO::FETCH_ASSOC);

        if ($SongToDelete) {
            $StatementDelete = $DatabaseConnection->prepare("DELETE FROM songs WHERE id = ? AND user_id = ?");
            $StatementDelete->execute([$SongIdToDelete, $UserId]);

            if ($StatementDelete->rowCount() > 0) {
                // Delete the actual file from the server
                if (file_exists($SongToDelete['file_path'])) {
                    unlink($SongToDelete['file_path']);
                }
                $MessageStatus = 'Song deleted successfully!';
                $MessageType = 'SuccessMessageClass';
            } else {
                $MessageStatus = 'Song not found or you do not have permission to delete it.';
                $MessageType = 'ErrorMessageClass';
            }
        } else {
            $MessageStatus = 'Song not found or you do not have permission to delete it.';
            $MessageType = 'ErrorMessageClass';
        }
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error deleting song: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}

// Fetch user's songs
try {
    $StatementSongs = $DatabaseConnection->prepare("SELECT s.id, s.title, s.artist, s.album, g.name AS genre_name, s.file_path, s.upload_date, s.play_count FROM songs s LEFT JOIN genres g ON s.genre_id = g.id WHERE s.user_id = ? ORDER BY s.upload_date DESC");
    $StatementSongs->execute([$UserId]);
    $UserSongs = $StatementSongs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    $MessageStatus = 'Error fetching songs: ' . $ExceptionObject->getMessage();
    $MessageType = 'ErrorMessageClass';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Music - SocialMusic</title>
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
        <div class="ContainerClass">
            <h2 style="text-align: center; margin-bottom: 30px; background: var(--gradient-bg); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">My Uploaded Music</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($UserSongs)): ?>
                <p style="text-align: center;">You haven't uploaded any music yet. <a href="upload.php" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Upload now!</a></p>
            <?php else: ?>
                <div class="TableContainerClass">
                    <table class="DataTableClass">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Artist</th>
                                <th>Album</th>
                                <th>Genre</th>
                                <th>Upload Date</th>
                                <th>Plays</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($UserSongs as $SongItem): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($SongItem['title']); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['artist']); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['album'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['genre_name'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($SongItem['upload_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['play_count']); ?></td>
                                    <td>
                                        <audio controls style="width: 150px; height: 30px;">
                                            <source src="<?php echo htmlspecialchars($SongItem['file_path']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                        <br>
                                        <a href="my_music.php?action=delete&id=<?php echo htmlspecialchars($SongItem['id']); ?>" onclick="return confirm('Are you sure you want to delete this song?');" style="color: #ff4d4d;">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="FooterClass">
        <div class="ContainerClass">
            <p>&copy; <?php echo date("Y"); ?> SocialMusic. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
