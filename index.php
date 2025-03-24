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
        <h1>Quiz 1 Task 1</h1>
    </div>

    <div class="div2">
        <form action="" method="post">
            <table>
                <tr>
                    <td><b>პროდუქტი</b></td>
                    <td><b>ფასი</b></td>
                    <td><b>აირჩიე</b></td>
                </tr>
                <tr>
                    <td>ლეპტოპი</td>
                    <td name="td1">2500</td>
                    <td><button name="button1">დაამატე კალათაში</button></td>
                </tr>
                <tr>
                    <td>სმარტფონი</td>
                    <td name="td2">1200</td>
                    <td><button name="button2">დაამატე კალათაში</button></td>
                </tr>
                <tr>
                    <td>ყურსასმენები</td>
                    <td name="td3">300</td>
                    <td><button name="button3">დაამატე კალათაში</button></td>
                </tr>
                <tr>
                    <td>მონიტორი</td>
                    <td name="td4">800</td>
                    <td><button name="button4">დაამატე კალათაში</button></td>
                </tr>
                <tr>
                    <td>კლავიატურა</td>
                    <td name="td5">150</td>
                    <td><button name="button5">დაამატე კალათაში</button></td>
                </tr>
            </table>

            <br>
            <br>
            <br>
            <button name="button6">Calculate Sum</button>

            <?php

                $ItemsSum = 0;

                function CalcSumOfItems($ItemsSum) {
                    if (isset($_POST['button1'])) {
                        $ItemsSum += 2500;
                    }
                    else if (isset($_POST['button2'])) {
                        $ItemsSum += 1200;
                    }
                    else if (isset($_POST['button3'])) {
                        $ItemsSum += 300;
                    }
                    else if (isset($_POST['button4'])) {
                        $ItemsSum += 800;
                    }
                    else if (isset($_POST['button3'])) {
                        $ItemsSum += 150;
                    }
                    return $ItemsSum;
                }

                if (isset($_POST['button6'])) {

                    CalcSumOfItems($ItemsSum);

                    echo $ItemsSum;

                }

            ?>

        </form>
    </div>
</body>
</html>