<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Giorgi_Poladauri_Quiz1</title>
</head>
<body>
    <div class="div1">
        <h1>Quiz 1 Task 2</h1>
    </div>

    <div class="div2">
        <form action="" method="post">
            <select name="" id="">
                <option value="<?php $RanNum = rand(10, 99); echo $RanNum; ?>" name="option1"></option>
            </select>

            <select name="" id="">
                <option value="<?php $RanNum = rand(100, 999); echo $RanNum; ?>" name="option2"></option>
            </select>

            <select name="" id="">
                <option value="<?php $RanNum = rand(1000, 9999); echo $RanNum; ?>" name="option3"></option>
            </select>

            <br>
            <br>
            <br>
            <br>
            <button name="button1">Calculate Sum</button>
            <br>
            <br>
            <br>
            <button name="button2">Calculate Average</button>
            <br>
            <br>
            <br>
            <button name="button3">Calculate Multiply</button>


            <?php
                function CalcSum() {
                    $SelNum1 = $_POST['option1']; 
                    $SelNum2 = $_POST['option2']; 
                    $SelNum3 = $_POST['option3'];

                    $SumOfNums = $SelNum1 + $SelNum2 + $SelNum3;
                    
                    return $SumOfNums;
                }

                function CalcAverage() {
                    $SelNum1 = $_POST['option1']; 
                    $SelNum2 = $_POST['option2']; 
                    $SelNum3 = $_POST['option3']; 

                    $AvgOfNums = ($SelNum1 + $SelNum2 + $SelNum3) / 3;

                    return $AvgOfNums;
                }

                function CalcMultiply() {
                    $SelNum1 = $_POST['option1']; 
                    $SelNum2 = $_POST['option2']; 
                    $SelNum3 = $_POST['option3'];

                    $MultOfNums = $SelNum1 * $SelNum2 * $SelNum3;

                    return $MultOfNums;
                }

                if(isset($_POST['button1'])) {
                    CalcSum();
                    echo $SumOfNums;
                }

                else if(isset($_POST['button2'])) {
                    CalcAverage();
                    echo $AvgOfNums;
                }

                else if(isset($_POST['button3'])) {
                    CalcMultiply();
                    echo $MultOfNums;
                }
            ?>

        </form>
    </div>
</body>
</html>