<?php
session_start();
require_once 'config.php';

$MessageStatus = '';
$MessageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UsernameOrEmailInput = trim($_POST['UsernameOrEmailInput']);
    $PasswordInput = $_POST['PasswordInput'];

    if (empty($UsernameOrEmailInput) || empty($PasswordInput)) {
        $MessageStatus = 'All fields are required.';
        $MessageType = 'ErrorMessageClass';
    } else {
        try {
            $StatementLogin = $DatabaseConnection->prepare("SELECT id, username, password_hash, is_admin FROM users WHERE username = ? OR email = ?");
            $StatementLogin->execute([$UsernameOrEmailInput, $UsernameOrEmailInput]);
            $UserRow = $StatementLogin->fetch(PDO::FETCH_ASSOC);

            if ($UserRow && password_verify($PasswordInput, $UserRow['password_hash'])) {
                $_SESSION['UserLoggedIn'] = true;
                $_SESSION['UserId'] = $UserRow['id'];
                $_SESSION['Username'] = $UserRow['username'];
                $_SESSION['IsAdmin'] = (bool)$UserRow['is_admin'];
                header('Location: index.php');
                exit();
            } else {
                $MessageStatus = 'Invalid username/email or password.';
                $MessageType = 'ErrorMessageClass';
            }
        } catch (PDOException $ExceptionObject) {
            $MessageStatus = 'Error: ' . $ExceptionObject->getMessage();
            $MessageType = 'ErrorMessageClass';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SocialMusic</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="HeaderClass">
        <div class="ContainerClass HeaderContentClass">
            <a href="index.php" class="LogoClass">SocialMusic</a>
            <nav class="NavigationClass">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="MainContentClass">
        <div class="CardClass">
            <h2>Login</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="FormGroupClass">
                    <label for="UsernameOrEmailInput">Username or Email:</label>
                    <input type="text" id="UsernameOrEmailInput" name="UsernameOrEmailInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="PasswordInput">Password:</label>
                    <input type="password" id="PasswordInput" name="PasswordInput" required>
                </div>
                <button type="submit" class="ButtonClass">Login</button>
            </form>
            <p style="text-align: center; margin-top: 20px;">Don't have an account? <a href="register.php" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Register here</a></p>
        </div>
    </main>

    <footer class="FooterClass">
        <div class="ContainerClass">
            <p>&copy; <?php echo date("Y"); ?> SocialMusic. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
