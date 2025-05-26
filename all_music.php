<?php
session_start();
require_once 'config.php';

$AllSongs = [];
$MessageStatus = '';
$MessageType = '';

if (isset($_GET['action']) && $_GET['action'] === 'play' && isset($_GET['id'])) {
    $SongIdToPlay = $_GET['id'];
    try {
        $StatementUpdatePlayCount = $DatabaseConnection->prepare("UPDATE songs SET play_count = play_count + 1 WHERE id = ?");
        $StatementUpdatePlayCount->execute([$SongIdToPlay]);
    } catch (PDOException $ExceptionObject) {
    }
}

try {
    $StatementAllSongs = $DatabaseConnection->query("SELECT s.id, s.title, s.artist, s.album, g.name AS genre_name, u.username AS uploader_username, s.file_path, s.upload_date, s.play_count FROM songs s LEFT JOIN genres g ON s.genre_id = g.id LEFT JOIN users u ON s.user_id = u.id ORDER BY s.upload_date DESC");
    $AllSongs = $StatementAllSongs->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Browse All Music - SocialMusic</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="HeaderClass">
        <div class="ContainerClass HeaderContentClass">
            <a href="index.php" class="LogoClass">SocialMusic</a>
            <nav class="NavigationClass">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['UserLoggedIn'])): ?>
                        <li><a href="upload.php">Upload Music</a></li>
                        <li><a href="my_music.php">My Music</a></li>
                        <li><a href="playlists.php">Playlists</a></li>
                        <li><a href="all_music.php">Browse All Music</a></li>
                        <?php if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin']): ?>
                            <li><a href="admin_panel.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['Username']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="MainContentClass">
        <div class="ContainerClass">
            <h2 style="text-align: center; margin-bottom: 30px; background: var(--gradient-bg); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">All Available Music</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($AllSongs)): ?>
                <p style="text-align: center;">No music has been uploaded yet.</p>
            <?php else: ?>
                <div class="TableContainerClass">
                    <table class="DataTableClass">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Artist</th>
                                <th>Album</th>
                                <th>Genre</th>
                                <th>Uploader</th>
                                <th>Upload Date</th>
                                <th>Plays</th>
                                <th>Listen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($AllSongs as $SongItem): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($SongItem['title']); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['artist']); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['album'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['genre_name'] ?: 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['uploader_username']); ?></td>
                                    <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($SongItem['upload_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($SongItem['play_count']); ?></td>
                                    <td>
                                        <audio controls style="width: 150px; height: 30px;" onplay="window.location.href='all_music.php?action=play&id=<?php echo htmlspecialchars($SongItem['id']); ?>'">
                                            <source src="<?php echo htmlspecialchars($SongItem['file_path']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
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
