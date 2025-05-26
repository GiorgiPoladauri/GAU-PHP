<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialMusic - Your Music Portal</title>
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
            <?php if (isset($_SESSION['UserLoggedIn'])): ?>
                <h1 style="text-align: center; margin-bottom: 40px; background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</h1>
                <div class="DashboardGridClass">
                    <div class="DashboardCardClass">
                        <h3>Upload Your Music</h3>
                        <p>Share your creations with the world.</p>
                        <a href="upload.php" class="ButtonClass">Upload Now</a>
                    </div>
                    <div class="DashboardCardClass">
                        <h3>My Music</h3>
                        <p>Manage and listen to your uploaded songs.</p>
                        <a href="my_music.php" class="ButtonClass">View My Music</a>
                    </div>
                    <div class="DashboardCardClass">
                        <h3>Create Playlists</h3>
                        <p>Organize your favorite tracks.</p>
                        <a href="playlists.php" class="ButtonClass">Manage Playlists</a>
                    </div>
                    <div class="DashboardCardClass">
                        <h3>Browse All Music</h3>
                        <p>Discover new songs from other artists.</p>
                        <a href="all_music.php" class="ButtonClass">Explore</a>
                    </div>
                    <?php if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin']): ?>
                        <div class="DashboardCardClass">
                            <h3>Admin Panel</h3>
                            <p>Manage users, songs, and genres.</p>
                            <a href="admin_panel.php" class="ButtonClass">Go to Admin</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="CardClass">
                    <h2>Join SocialMusic Today!</h2>
                    <p style="text-align: center; margin-bottom: 20px;">Discover, upload, and share your favorite music.</p>
                    <a href="register.php" class="ButtonClass">Register Now</a>
                    <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.php" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Login here</a></p>
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
