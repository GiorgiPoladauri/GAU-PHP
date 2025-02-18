<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salaries Form</title>
</head>

<h2>Salary Form</h2>

<form method="get" action="Salaries.php">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"><br><br>

    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname"  ><br><br>

    <label for="position">Position:</label>
    <input type="text" id="position" name="position"  ><br><br>

    <label for="salary">Salary:</label>
    <input type="number" id="salary" name="salary"  ><br><br>

    <label for="tax_percentage">Tax Percentage:</label>
    <input type="number" id="tax_percentage" name="tax_percentage" value="20" min="0" max="100"><br><br>

    <input type="submit" name="submit" value="Submit">

</form>

        
<?php

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['submit'])) {
        $name = $_GET['name'];
        $surname = $_GET['surname'];
        $position = $_GET['position'];
        $salary = $_GET['salary'];
        $tax_percentage = $_GET['tax_percentage'] ?? 20;
        $tax_amount = ($tax_percentage / 100) * $salary;
        $net_salary = $salary - $tax_amount;
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['submit'])) {
        echo "<h3>Salary Details</h3>";
        echo "<table border='1'>
                <tr>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Tax Percentage</th>
                    <th>Tax Amount</th>
                    <th>Net Salary</th>
                </tr>";
        echo "<tr>
                <td>" . htmlspecialchars($name) . "</td>
                <td>" . htmlspecialchars($surname) . "</td>
                <td>" . htmlspecialchars($position) . "</td>
                <td>" . htmlspecialchars($salary) . "</td>
                <td>" . htmlspecialchars($tax_percentage) . "%</td>
                <td>" . number_format($tax_amount, 2) . "</td>
                <td>" . number_format($net_salary, 2) . "</td>
            </tr>";
        echo "</table>";
    }
?>


</body>

</html>