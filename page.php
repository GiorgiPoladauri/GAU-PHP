<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>HomeWork3GiorgiPoladauriPHP</title>
</head>
<body>

    <h1>Halo</h1>

    <div class="div1">
        <form action="" method="post">

            <?php

                function ContainsNum($Url) {
                    if(preg_match('/\d/', $Url)) {
                        return true;
                    }
                    return false;
                }

                $Url = "https://gitlab.com/gau8635246/gau_php-mysql_2025_1/-/blob/main/Lecture_4/page.php?ref_type=heads";

                if(ContainsNum($Url)) {
                    echo "Hello, it does contain nums.";
                }
                else {
                    echo "Hi, it does't have any nums init.";
                }

            ?>

        </form>
    </div>
</body>
</html>