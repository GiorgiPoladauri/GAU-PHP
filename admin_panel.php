<?php
session_start();
require_once 'config.php';

// Redirect if not logged in or not an admin
if (!isset($_SESSION['UserLoggedIn']) || !$_SESSION['IsAdmin']) {
    header('Location: index.php');
    exit();
}

$MessageStatus = '';
$MessageType = '';
$UsersArray = [];
$SongsArray = [];
$GenresArray = [];

// Handle User Actions (Delete User)
if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
    $UserIdToDelete = $_GET['id'];
    // Prevent admin from deleting themselves
    if ($UserIdToDelete == $_SESSION['UserId']) {
        $MessageStatus = 'You cannot delete your own admin account.';
        $MessageType = 'ErrorMessageClass';
    } else {
        try {
            $StatementDeleteUser = $DatabaseConnection->prepare("DELETE FROM users WHERE id = ?");
            $StatementDeleteUser->execute([$UserIdToDelete]);
            if ($StatementDeleteUser->rowCount() > 0) {
                $MessageStatus = 'User deleted successfully!';
                $MessageType = 'SuccessMessageClass';
            } else {
                $MessageStatus = 'User not found.';
                $MessageType = 'ErrorMessageClass';
            }
        } catch (PDOException $ExceptionObject) {
            $MessageStatus = 'Error deleting user: ' . $ExceptionObject->getMessage();
            $MessageType = 'ErrorMessageClass';
        }
    }
}

// Handle Song Actions (Delete Song)
if (isset($_GET['action']) && $_GET['action'] === 'delete_song' && isset($_GET['id'])) {
    $SongIdToDelete = $_GET['id'];
    try {
        // Get file path before deleting from DB
        $StatementFilePath = $DatabaseConnection->prepare("SELECT file_path FROM songs WHERE id = ?");
        $StatementFilePath->execute([$SongIdToDelete]);
        $SongToDelete = $StatementFilePath->fetch(PDO::FETCH_ASSOC);

        $StatementDeleteSong = $DatabaseConnection->prepare("DELETE FROM songs WHERE id = ?");
        $StatementDeleteSong->execute([$SongIdToDelete]);
        if ($StatementDeleteSong->rowCount() > 0) {
            if ($SongToDelete && file_exists($SongToDelete['file_path'])) {
                unlink($SongToDelete['file_path']); // Delete the actual file
            }
            $MessageStatus = 'Song deleted successfully!';
            $MessageType = 'SuccessMessageClass';
        } else {
            $MessageStatus = 'Song not found.';
            $MessageType = 'ErrorMessageClass';
        }
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error deleting song: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}

// Handle Genre Actions (Add Genre)
if (isset($_POST['AddGenre'])) {
    $NewGenreName = trim($_POST['NewGenreName']);
    if (empty($NewGenreName)) {
        $MessageStatus = 'Genre name cannot be empty.';
        $MessageType = 'ErrorMessageClass';
    } else {
        try {
            $StatementInsertGenre = $DatabaseConnection->prepare("INSERT INTO genres (name) VALUES (?)");
            $StatementInsertGenre->execute([$NewGenreName]);
            $MessageStatus = 'Genre added successfully!';
            $MessageType = 'SuccessMessageClass';
        } catch (PDOException $ExceptionObject) {
            $MessageStatus = 'Error adding genre: ' . $ExceptionObject->getMessage();
            $MessageType = 'ErrorMessageClass';
        }
    }
}

// Handle Genre Actions (Delete Genre)
if (isset($_GET['action']) && $_GET['action'] === 'delete_genre' && isset($_GET['id'])) {
    $GenreIdToDelete = $_GET['id'];
    try {
        $StatementDeleteGenre = $DatabaseConnection->prepare("DELETE FROM genres WHERE id = ?");
        $StatementDeleteGenre->execute([$GenreIdToDelete]);
        if ($StatementDeleteGenre->rowCount() > 0) {
            $MessageStatus = 'Genre deleted successfully!';
            $MessageType = 'SuccessMessageClass';
        } else {
            $MessageStatus = 'Genre not found.';
            $MessageType = 'ErrorMessageClass';
        }
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error deleting genre: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}

// Fetch all users
try {
    $StatementUsers = $DatabaseConnection->query("SELECT id, username, email, registration_date, is_admin FROM users ORDER BY registration_date DESC");
    $UsersArray = $StatementUsers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    $MessageStatus = 'Error fetching users: ' . $ExceptionObject->getMessage();
    $MessageType = 'ErrorMessageClass';
}

// Fetch all songs with uploader username and genre name
try {
    $StatementSongs = $DatabaseConnection->query("SELECT s.id, s.title, s.artist, s.album, g.name AS genre_name, u.username AS uploader_username, s.upload_date, s.play_count FROM songs s LEFT JOIN genres g ON s.genre_id = g.id LEFT JOIN users u ON s.user_id = u.id ORDER BY s.upload_date DESC");
    $SongsArray = $StatementSongs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    $MessageStatus = 'Error fetching songs: ' . $ExceptionObject->getMessage();
    $MessageType = 'ErrorMessageClass';
}

// Fetch all genres
try {
    $StatementGenres = $DatabaseConnection->query("SELECT id, name FROM genres ORDER BY name ASC");
    $GenresArray = $StatementGenres->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    $MessageStatus = 'Error fetching genres: ' . $ExceptionObject->getMessage();
    $MessageType = 'ErrorMessageClass';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SocialMusic</title>
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
            <h2 style="text-align: center; margin-bottom: 30px; background: var(--gradient-bg); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Administration Panel</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>

            <div class="DashboardGridClass" style="grid-template-columns: 1fr;">
                <div class="CardClass" style="max-width: none;">
                    <h3>Manage Users</h3>
                    <?php if (empty($UsersArray)): ?>
                        <p style="text-align: center;">No users registered yet.</p>
                    <?php else: ?>
                        <div class="TableContainerClass">
                            <table class="DataTableClass">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Registration Date</th>
                                        <th>Admin</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($UsersArray as $User): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($User['id']); ?></td>
                                            <td><?php echo htmlspecialchars($User['username']); ?></td>
                                            <td><?php echo htmlspecialchars($User['email']); ?></td>
                                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($User['registration_date']))); ?></td>
                                            <td><?php echo $User['is_admin'] ? 'Yes' : 'No'; ?></td>
                                            <td>
                                                <?php if ($User['id'] != $_SESSION['UserId']): // Prevent admin from deleting self ?>
                                                    <a href="admin_panel.php?action=delete_user&id=<?php echo htmlspecialchars($User['id']); ?>" onclick="return confirm('Are you sure you want to delete this user and all their associated data (songs, playlists)?');" style="color: #ff4d4d;">Delete</a>
                                                <?php else: ?>
                                                    (Current Admin)
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="CardClass" style="max-width: none; margin-top: 30px;">
                    <h3>Manage Songs</h3>
                    <?php if (empty($SongsArray)): ?>
                        <p style="text-align: center;">No songs uploaded yet.</p>
                    <?php else: ?>
                        <div class="TableContainerClass">
                            <table class="DataTableClass">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Artist</th>
                                        <th>Uploader</th>
                                        <th>Genre</th>
                                        <th>Plays</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($SongsArray as $Song): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($Song['id']); ?></td>
                                            <td><?php echo htmlspecialchars($Song['title']); ?></td>
                                            <td><?php echo htmlspecialchars($Song['artist']); ?></td>
                                            <td><?php echo htmlspecialchars($Song['uploader_username']); ?></td>
                                            <td><?php echo htmlspecialchars($Song['genre_name'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($Song['play_count']); ?></td>
                                            <td>
                                                <a href="admin_panel.php?action=delete_song&id=<?php echo htmlspecialchars($Song['id']); ?>" onclick="return confirm('Are you sure you want to delete this song?');" style="color: #ff4d4d;">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="CardClass" style="max-width: none; margin-top: 30px;">
                    <h3>Manage Genres</h3>
                    <form action="admin_panel.php" method="POST" style="margin-bottom: 20px;">
                        <div class="FormGroupClass">
                            <label for="NewGenreName">Add New Genre:</label>
                            <input type="text" id="NewGenreName" name="NewGenreName" required>
                        </div>
                        <button type="submit" name="AddGenre" class="ButtonClass">Add Genre</button>
                    </form>

                    <?php if (empty($GenresArray)): ?>
                        <p style="text-align: center;">No genres defined yet.</p>
                    <?php else: ?>
                        <div class="TableContainerClass">
                            <table class="DataTableClass">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Genre Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($GenresArray as $Genre): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($Genre['id']); ?></td>
                                            <td><?php echo htmlspecialchars($Genre['name']); ?></td>
                                            <td>
                                                <a href="admin_panel.php?action=delete_genre&id=<?php echo htmlspecialchars($Genre['id']); ?>" onclick="return confirm('Are you sure you want to delete this genre? Songs associated with this genre will have their genre set to NULL.');" style="color: #ff4d4d;">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="FooterClass">
        <div class="ContainerClass">
            <p>&copy; <?php echo date("Y"); ?> SocialMusic. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
