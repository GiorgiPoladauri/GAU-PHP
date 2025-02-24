<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Quiz PHP</title>
</head>
	<body>
		<form method="post">
			<div>
				<p>1. What is the capital of France?<br></p>
				<input type="radio" name="q1" value="a"> Berlin<br>
				<input type="radio" name="q1" value="b"> Madrid<br>
				<input type="radio" name="q1" value="c"> Paris<br>
				<input type="radio" name="q1" value="d"> London<br>
			</div>
			<div>
				<p>2. Who is The US President now?<br></p>
				<input type="radio" name="q2" value="a"> B.Obama<br>
				<input type="radio" name="q2" value="b"> V.Putin<br>
				<input type="radio" name="q2" value="c"> D.Trump<br>
				<input type="radio" name="q2" value="d"> G.Bush<br>
			</div>
			<div>
				<p>3. Which is the fastest car manufacturer?<br></p>
				<input type="radio" name="q3" value="a"> BMW<br>
				<input type="radio" name="q3" value="b"> Mercedes Benz<br>
				<input type="radio" name="q3" value="c"> Lamborghini<br>
				<input type="radio" name="q3" value="d"> Lada<br>
			</div>
			<div>
				<p>4. Who wrote 'VefxisTyaosani'?<br></p>
				<textarea name="q4" rows="3" cols="40"></textarea>
			</div>
			<div>
				<p>5. What is the formula for water?<br></p>
				<textarea name="q5" rows="3" cols="40"></textarea>
				<input type="submit" value="Submit">
			</div>
		</form>

        <?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$score = 0;
				if (isset($_POST["q1"]) && $_POST["q1"] == "c") {
					$score++;
				}
				if (isset($_POST["q2"]) && $_POST["q2"] == "b") {
					$score++;
				}
				if (isset($_POST["q3"]) && $_POST["q3"] == "d") {
					$score++;
				}
				if (isset($_POST["q4"]) && $_POST["q4"] == "shota rustaveli") {
					$score++;
				}
				if (isset($_POST["q5"]) && $_POST["q5"] == "h2o") {
					$score++;
				}
				echo "<br>You got " . $score . " correct answers.";
    		}   
		?>

    </body>
</html>
