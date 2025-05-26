<?php
session_start();
require_once 'config.php';

$MessageStatus = '';
$MessageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UsernameInput = trim($_POST['UsernameInput']);
    $EmailInput = trim($_POST['EmailInput']);
    $PasswordInput = $_POST['PasswordInput'];
    $ConfirmPasswordInput = $_POST['ConfirmPasswordInput'];

    if (empty($UsernameInput) || empty($EmailInput) || empty($PasswordInput) || empty($ConfirmPasswordInput)) {
        $MessageStatus = 'All fields are required.';
        $MessageType = 'ErrorMessageClass';
    } elseif (!filter_var($EmailInput, FILTER_VALIDATE_EMAIL)) {
        $MessageStatus = 'Invalid email format.';
        $MessageType = 'ErrorMessageClass';
    } elseif ($PasswordInput !== $ConfirmPasswordInput) {
        $MessageStatus = 'Passwords do not match.';
        $MessageType = 'ErrorMessageClass';
    } elseif (strlen($PasswordInput) < 6) {
        $MessageStatus = 'Password must be at least 6 characters long.';
        $MessageType = 'ErrorMessageClass';
    } else {
        $PasswordHash = password_hash($PasswordInput, PASSWORD_DEFAULT);

        try {
            // Check if username or email already exists
            $StatementCheck = $DatabaseConnection->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $StatementCheck->execute([$UsernameInput, $EmailInput]);
            if ($StatementCheck->rowCount() > 0) {
                $MessageStatus = 'Username or email already exists.';
                $MessageType = 'ErrorMessageClass';
            } else {
                $StatementInsert = $DatabaseConnection->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $StatementInsert->execute([$UsernameInput, $EmailInput, $PasswordHash]);
                $MessageStatus = 'Registration successful! You can now login.';
                $MessageType = 'SuccessMessageClass';
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
    <title>Register - SocialMusic</title>
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
            <h2>Register</h2>
            <?php if (!empty($MessageStatus)): ?>
                <div class="MessageClass <?php echo $MessageType; ?>">
                    <?php echo htmlspecialchars($MessageStatus); ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <div class="FormGroupClass">
                    <label for="UsernameInput">Username:</label>
                    <input type="text" id="UsernameInput" name="UsernameInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="EmailInput">Email:</label>
                    <input type="email" id="EmailInput" name="EmailInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="PasswordInput">Password:</label>
                    <input type="password" id="PasswordInput" name="PasswordInput" required>
                </div>
                <div class="FormGroupClass">
                    <label for="ConfirmPasswordInput">Confirm Password:</label>
                    <input type="password" id="ConfirmPasswordInput" name="ConfirmPasswordInput" required>
                </div>
                <button type="submit" class="ButtonClass">Register</button>
            </form>
            <p style="text-align: center; margin-top: 20px;">Already have an account? <a href="login.php" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">Login here</a></p>
        </div>
    </main>

    <footer class="FooterClass">
        <div class="ContainerClass">
            <p>&copy; <?php echo date("Y"); ?> SocialMusic. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
