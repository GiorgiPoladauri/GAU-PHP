<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>GiorgiPoladauriQuiz2PHP</title>
</head>
<body>
    <div class="div1">
        <h1>Welcome To Students List App</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="name" placeholder="StudentName" name="input1" required>
            <br>
            <br>
            <input type="text" placeholder="Subject" name="input2" required>
            <br>
            <br>
            <input type="text" placeholder="Midterm Points" name="input3" required>
            <br>
            <br>
            <input type="text" placeholder="Final Exam Points" name="input4" required>
            <br>
            <br>
            <button name="button1">Submit</button>
        </form>

        <?php
        include "connectsql.php";
            if(isset($_POST['button1'])) {
                $name = $_POST['input1'];
                $subject = $_POST['input2'];
                $midterm = $_POST['input3'];
                $final = $_POST['input4'];

                if (strlen($name) <= 30) {
                    $sqlcommand1 = $conn->prepare("insert into grades (subject, midterm, final) values (?, ?, ?)");
                    $sqlcommand1->bind_param("sii", $subject, $midterm, $final);
                    $sqlcommand1->execute();

                    $sqlcommand2 = $conn->prepare("insert into students (name) values (?)");
                    $sqlcommand2->bind_param("s", $name);
                    $sqlcommand2->execute();
                }
                else {
                    echo "<br>";
                    echo "Your Name Is Too Long !";
                }
            }
        ?>

        <form action="" method="post">
            <br>
            <br>
            <button name="button2">Show All Students In Database</button>
        </form>

        <?php
            if (isset($_POST['button2'])) {
                $sqlcommand1 = $conn->prepare("SELECT * FROM grades");
                $sqlcommand1->execute();
                $result1 = $sqlcommand1->get_result();
                    
                while ($row1 = $result1->fetch_assoc()) {
                        echo "<br>" . "Subject: " . $row1['subject'] . " Midterm : " . $row1['midterm'] . " Final : " . $row1['final'] . "<br>";
                    }
            }  
        ?>

    </div>
</body>
</html>