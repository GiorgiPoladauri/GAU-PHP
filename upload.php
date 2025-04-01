<?php
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file_name = $_FILES['file']['name'];
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];
        
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];
        if (!in_array($file_type, $allowed_types)) {
            echo "Invalid file type!";
            exit;
        }

        $destination = $upload_dir . basename($file_name);
        
        if (move_uploaded_file($file_tmp_name, $destination)) {
            echo "File uploaded successfully!";
        } else {
            echo "File upload failed!";
        }
    } 
    else {
        echo "No file uploaded or error occurred.";
    }
}
?>
