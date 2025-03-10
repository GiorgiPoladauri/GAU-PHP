<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>HomeWork3GiorgiPoladauriPHP</title>
</head>
<body>

    <h1>HomeWork3GiorgiPoladauriPHP</h1>

    <div class="div0">
        <form action="" method="post">

            <?php

                function showTable() {

                    echo "<table border='1'>";

                    for ($row = 0; $row < 10; $row++) {

                        echo "<tr>";

                        for ($col = 0; $col < 10; $col++) {
                            $randomNumber = rand(10, 99);
                            echo "<td>$randomNumber</td>";
                        }

                        echo "</tr>"; 
                    }
                    echo "</table>";
                }
                
                showTable();            
            ?>
        </form>
    </div>

    <div class="div1">

        <form action="" method="post">

            <br>
            <br>
            <br>

            <input type="number" placeholder="Input Number for Length" name="input1" value="<?php echo isset($_POST['input1']) ? htmlspecialchars($_POST['input1']) : ''; ?>">

            <button name="button1">Calculate</button>

            <?php
                function NumLen() {
                    if (isset($_POST['button1'])) {
                        $InputedNumber = $_POST['input1'];
                        $InputedNumLength = mb_strlen($InputedNumber);
                        echo "<br>";
                        echo "<br>";
                        echo "<br>";
                        echo "Length = $InputedNumLength";
                    }
                }

                NumLen();

            ?>
        </form>
    </div>

    <div class="div2">
        <form action="" method="post">

            <br>
            <br>
            <input type="password" name="input2" placeholder="Input Number for Test Pass" value="<?php echo isset($_POST['input2']) ? htmlspecialchars(isset($_POST['input2'])) : '' ?>">

            <button name="button2">CLICK ME DEAR</button>
            
            <?php
                function TestPassword() {

                    if (isset($_POST['button2'])) {

                        $InputedPassword = $_POST['input2'];

                        if(mb_strlen($InputedPassword) > 10) {
                            echo "<br>";
                            echo "<br>";
                            echo "<br>";
                            echo "<p>Good Job, KAREN.</p>";
                            return;
                        }

                        else if (mb_strlen($InputedPassword) > 5) {
                            echo "<br>";
                            echo "<br>";
                            echo "<br>";
                            echo "<p>It seems normal, KAREN.</p>";
                            return;
                        }
                        echo "<br>";
                        echo "<br>";
                        echo "<br>";
                        echo "<p>It is weak, KAREN.</p>";
                    }
                }

                TestPassword();

            ?>
        </form>
    </div>

</body>
</html>