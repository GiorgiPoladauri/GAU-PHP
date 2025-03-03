<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeWork3Form</title>
</head>
<body>
    
    <div class="div1">
        <form action="" method="POST">
            <h1>HomeWork 3 Page 2 PHP G.P</h1>
            <br>
            <input type="text" name="input1" placeholder="Name" value="<?php echo isset($_POST['input1']) ? $_POST['input1'] : ''; ?>">
            <br>
            <input type="email" name="input2" placeholder="Email" value="<?php echo isset($_POST['input2']) ? $_POST['input2'] : ''; ?>">
            <br>
            <input type="text" name="input3" placeholder="Website" value="<?php echo isset($_POST['input3']) ? $_POST['input3'] : ''; ?>">
            <br>
            <textarea name="Comments" placeholder="Comments"><?php echo isset($_POST['textarea1']) ? $_POST['textarea1'] : ''; ?></textarea>
            <br>
            <input type="radio" name="gender" value="Male" <?php echo isset($_POST['radioinput1']) && $_POST['radioinput1'] == 'Male' ? 'checked' : ''; ?>> Male
            <input type="radio" name="gender" value="Female" <?php echo isset($_POST['radioinput1']) && $_POST['radioinput1'] == 'Female' ? 'checked' : ''; ?>> Female
            <br>
            <button id="button1" type="submit">Submit</button>
            <br>
            <h1>Your Input :</h1>
            <br>
            <h4 id="h4_1"></h4>
        </form>
    </div>

    <?php

        $Name = "";
        $Email = "";
        $NameEror = "";
        $EmailEror = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["input1"])) {
                $NameEror = "Eror in inputed data ! Name is incorrect !";
            }
            else {
                $Name = $_POST["input1"];
                if (!preg_match("/^[a-zA-Z ]*$/", $Name)) {
                    $NameEror = "Only letters and white space are allowed in this field";
                }
            }
        }

        if (empty($_POST["input2"])) {
            $emailErr = "Email is required";
        } 
        else {
            $Email = InputedDataTestFunction($_POST["input2"]);
            if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                $EmailErr = "Invalid email format";
            }
        }

        function InputedDataTestFunction($Data) {
            $Data = trim($Data);
            $Data = stripslashes($Data);
            $Data = htmlspecialchars($Data);
            return $Data;
        }

        if (!empty($NameEror)) {
            echo "<p style='color: red;'>$NameEror</p>";
        }
        if (!empty($EmailEror)) {
            echo "<p style='color: red;'>$EmailEror</p>";
        }

    ?>

    <style>

        html {
            background-color:rgba(222, 212, 106, 0.56);
        }
        textarea {
            margin-top: 10px;
            margin-left: 10px;
            resize: none;
            height: 100px;
            border-radius: 12px;
            background-color:rgb(155, 215, 157);
        }
        input {
            margin-top: 10px;
            margin-left: 10px;
            border-radius: 12px;
            background-color:rgb(155, 215, 157);
        }
        button {
            margin-top: 10px;
            margin-left: 10px;
            border-radius: 12px;
            background-color:rgb(155, 215, 157);
        }

    </style>

</body>
</html>