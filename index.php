<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Work 2</title>
</head>
<body>
    <h1>Class Work 2</h1>
    <?php

        $students = [
            'dato'=>rand(0, 100),
            'gia'=>rand(0, 100),
            'lia'=>rand(0, 100),
            'ana'=>rand(0, 100),
            'vika'=>rand(0, 100),
        ];

        echo "<pre>";
        print_r($students);
        echo "</pre>";
        echo "<hr>";

        $sum = 0;
        foreach ($students as $student => $point) {
            echo "<div>$student - $point</div>";
            $sum += $point;
        }
        echo "<hr>";
        echo "<div>Sum = $sum</div>";
        $average = $sum / count($students);
        echo "<hr>";
        echo "<div>Average = $average</div>";
        echo "<hr>";
        foreach ($students as $student => $point) {
            if($point >= $average) echo "<div>$student - $point</div>";
        }
        
    ?>
</body>
</html>