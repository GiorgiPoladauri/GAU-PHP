<?php
session_start();
require_once 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['UserLoggedIn'])) {
    header('Location: login.php');
    exit();
}

$UserId = $_SESSION['UserId'];
$MessageStatus = '';
$MessageType = '';
$UserPlaylists = [];
$SongsAvailable = [];
$SelectedPlaylistSongs = [];
$SelectedPlaylistId = null;

// Fetch all songs for adding to playlists
try {
    $StatementAllSongs = $DatabaseConnection->query("SELECT id, title, artist FROM songs ORDER BY title ASC");
    $SongsAvailable = $StatementAllSongs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    // Handle error
}

// Handle playlist creation
if (isset($_POST['CreatePlaylist'])) {
    $PlaylistNameInput = trim($_POST['PlaylistNameInput']);
    if (empty($PlaylistNameInput)) {
        $MessageStatus = 'Playlist name cannot be empty.';
        $MessageType = 'ErrorMessageClass';
    } else {
        try {
            $StatementInsertPlaylist = $DatabaseConnection->prepare("INSERT INTO playlists (user_id, name) VALUES (?, ?)");
            $StatementInsertPlaylist->execute([$UserId, $PlaylistNameInput]);
            $MessageStatus = 'Playlist created successfully!';
            $MessageType = 'SuccessMessageClass';
        } catch (PDOException $ExceptionObject) {
            $MessageStatus = 'Error creating playlist: ' . $ExceptionObject->getMessage();
            $MessageType = 'ErrorMessageClass';
        }
    }
}

// Handle playlist deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete_playlist' && isset($_GET['id'])) {
    $PlaylistIdToDelete = $_GET['id'];
    try {
        $StatementDeletePlaylist = $DatabaseConnection->prepare("DELETE FROM playlists WHERE id = ? AND user_id = ?");
        $StatementDeletePlaylist->execute([$PlaylistIdToDelete, $UserId]);
        if ($StatementDeletePlaylist->rowCount() > 0) {
            $MessageStatus = 'Playlist deleted successfully!';
            $MessageType = 'SuccessMessageClass';
        } else {
            $MessageStatus = 'Playlist not found or you do not have permission to delete it.';
            $MessageType = 'ErrorMessageClass';
        }
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error deleting playlist: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}

// Handle adding song to playlist
if (isset($_POST['AddSongToPlaylist'])) {
    $PlaylistIdToAdd = $_POST['PlaylistIdToAdd'];
    $SongIdToAdd = $_POST['SongIdToAdd'];

    if (empty($PlaylistIdToAdd) || empty($SongIdToAdd)) {
        $MessageStatus = 'Please select both a playlist and a song.';
        $MessageType = 'ErrorMessageClass';
    } else {
        try {
            // Check if song is already in playlist
            $StatementCheck = $DatabaseConnection->prepare("SELECT COUNT(*) FROM playlist_songs WHERE playlist_id = ? AND song_id = ?");
            $StatementCheck->execute([$PlaylistIdToAdd, $SongIdToAdd]);
            if ($StatementCheck->fetchColumn() > 0) {
                $MessageStatus = 'Song is already in this playlist.';
                $MessageType = 'ErrorMessageClass';
            } else {
                $StatementAddSong = $DatabaseConnection->prepare("INSERT INTO playlist_songs (playlist_id, song_id) VALUES (?, ?)");
                $StatementAddSong->execute([$PlaylistIdToAdd, $SongIdToAdd]);
                $MessageStatus = 'Song added to playlist!';
                $MessageType = 'SuccessMessageClass';
            }
        } catch (PDOException $ExceptionObject) {
            $MessageStatus = 'Error adding song to playlist: ' . $ExceptionObject->getMessage();
            $MessageType = 'ErrorMessageClass';
        }
    }
}

// Handle removing song from playlist
if (isset($_GET['action']) && $_GET['action'] === 'remove_song' && isset($_GET['playlist_id']) && isset($_GET['song_id'])) {
    $PlaylistIdToRemove = $_GET['playlist_id'];
    $SongIdToRemove = $_GET['song_id'];
    try {
        // Verify user owns the playlist
        $StatementVerifyPlaylist = $DatabaseConnection->prepare("SELECT user_id FROM playlists WHERE id = ?");
        $StatementVerifyPlaylist->execute([$PlaylistIdToRemove]);
        $PlaylistOwner = $StatementVerifyPlaylist->fetchColumn();

        if ($PlaylistOwner == $UserId) {
            $StatementRemoveSong = $DatabaseConnection->prepare("DELETE FROM playlist_songs WHERE playlist_id = ? AND song_id = ?");
            $StatementRemoveSong->execute([$PlaylistIdToRemove, $SongIdToRemove]);
            if ($StatementRemoveSong->rowCount() > 0) {
                $MessageStatus = 'Song removed from playlist.';
                $MessageType = 'SuccessMessageClass';
            } else {
                $MessageStatus = 'Song not found in playlist.';
                $MessageType = 'ErrorMessageClass';
            }
        } else {
            $MessageStatus = 'You do not have permission to modify this playlist.';
            $MessageType = 'ErrorMessageClass';
        }
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error removing song: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}


// Fetch user's playlists
try {
    $StatementPlaylists = $DatabaseConnection->prepare("SELECT id, name FROM playlists WHERE user_id = ? ORDER BY name ASC");
    $StatementPlaylists->execute([$UserId]);
    $UserPlaylists = $StatementPlaylists->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ExceptionObject) {
    $MessageStatus = 'Error fetching playlists: ' . $ExceptionObject->getMessage();
    $MessageType = 'ErrorMessageClass';
}

// Fetch songs for a selected playlist
if (isset($_GET['view_playlist']) && !empty($_GET['view_playlist'])) {
    $SelectedPlaylistId = $_GET['view_playlist'];
    try {
        $StatementSelectedPlaylistSongs = $DatabaseConnection->prepare("
            SELECT s.id, s.title, s.artist, s.file_path
            FROM songs s
            JOIN playlist_songs ps ON s.id = ps.song_id
            WHERE ps.playlist_id = ?
            ORDER BY ps.added_date ASC
        ");
        $StatementSelectedPlaylistSongs->execute([$SelectedPlaylistId]);
        $SelectedPlaylistSongs = $StatementSelectedPlaylistSongs->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ExceptionObject) {
        $MessageStatus = 'Error fetching playlist songs: ' . $ExceptionObject->getMessage();
        $MessageType = 'ErrorMessageClass';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlists - SocialMusic</title>
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
            <h2 style="text-align: center; margin-bottom: 30px; background: var(--gradient-bg); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Your Playlists</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>

            <div class="DashboardGridClass" style="grid-template-columns: 1fr;">
                <div class="CardClass" style="max-width: none;">
                    <h3>Create New Playlist</h3>
                    <form action="playlists.php" method="POST">
                        <div class="FormGroupClass">
                            <label for="PlaylistNameInput">Playlist Name:</label>
                            <input type="text" id="PlaylistNameInput" name="PlaylistNameInput" required>
                        </div>
                        <button type="submit" name="CreatePlaylist" class="ButtonClass">Create Playlist</button>
                    </form>
                </div>

                <div class="CardClass" style="max-width: none; margin-top: 30px;">
                    <h3>Your Existing Playlists</h3>
                    <?php if (empty($UserPlaylists)): ?>
                        <p style="text-align: center;">You haven't created any playlists yet.</p>
                    <?php else: ?>
                        <div class="TableContainerClass">
                            <table class="DataTableClass">
                                <thead>
                                    <tr>
                                        <th>Playlist Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($UserPlaylists as $Playlist): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($Playlist['name']); ?></td>
                                            <td>
                                                <a href="playlists.php?view_playlist=<?php echo htmlspecialchars($Playlist['id']); ?>" class="ButtonClass" style="display: inline-block; width: auto; padding: 8px 15px; font-size: 0.9em; margin-top: 0; margin-right: 10px;">View/Edit</a>
                                                <a href="playlists.php?action=delete_playlist&id=<?php echo htmlspecialchars($Playlist['id']); ?>" onclick="return confirm('Are you sure you want to delete this playlist?');" style="color: #ff4d4d;">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($SelectedPlaylistId): ?>
                    <div class="CardClass" style="max-width: none; margin-top: 30px;">
                        <h3>Songs in Selected Playlist</h3>
                        <?php if (empty($SelectedPlaylistSongs)): ?>
                            <p style="text-align: center;">This playlist is empty.</p>
                        <?php else: ?>
                            <div class="TableContainerClass">
                                <table class="DataTableClass">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Artist</th>
                                            <th>Listen</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($SelectedPlaylistSongs as $Song): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($Song['title']); ?></td>
                                                <td><?php echo htmlspecialchars($Song['artist']); ?></td>
                                                <td>
                                                    <audio controls style="width: 150px; height: 30px;">
                                                        <source src="<?php echo htmlspecialchars($Song['file_path']); ?>" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </td>
                                                <td>
                                                    <a href="playlists.php?action=remove_song&playlist_id=<?php echo htmlspecialchars($SelectedPlaylistId); ?>&song_id=<?php echo htmlspecialchars($Song['id']); ?>" onclick="return confirm('Are you sure you want to remove this song from the playlist?');" style="color: #ff4d4d;">Remove</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <h4 style="margin-top: 30px; margin-bottom: 20px; background: var(--gradient-bg); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Add Song to This Playlist</h4>
                        <form action="playlists.php?view_playlist=<?php echo htmlspecialchars($SelectedPlaylistId); ?>" method="POST">
                            <input type="hidden" name="PlaylistIdToAdd" value="<?php echo htmlspecialchars($SelectedPlaylistId); ?>">
                            <div class="FormGroupClass">
                                <label for="SongIdToAdd">Select Song:</label>
                                <select id="SongIdToAdd" name="SongIdToAdd" required>
                                    <option value="">Select a Song</option>
                                    <?php foreach ($SongsAvailable as $SongOption): ?>
                                        <option value="<?php echo htmlspecialchars($SongOption['id']); ?>">
                                            <?php echo htmlspecialchars($SongOption['title']); ?> - <?php echo htmlspecialchars($SongOption['artist']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="AddSongToPlaylist" class="ButtonClass">Add Song</button>
                        </form>
                    </div>
                <?php endif; ?>
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
