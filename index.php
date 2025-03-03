<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeWork2</title>
</head>
<body>

    <h1>HomeWork 3 Page 2 PHP G.P</h1>
    
    <?php
        $rows = 6;
        $cols = 5;
        $matrix = [];

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                $matrix[$i][$j] = $i + $j;
            }
        }

        echo "<table border='1' cellpadding='5' cellspacing='0'>";

        for ($i = 0; $i < $rows; $i++) {
            echo "<tr>";
            for ($j = 0; $j < $cols; $j++) {
                echo "<td>" . $matrix[$i][$j] . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    ?>


</body>
</html>