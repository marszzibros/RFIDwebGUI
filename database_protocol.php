<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Jay Hwasung Jung">
        <meta name="description" content="">
    </head>
    <?php
    include 'connect-DB.php';
    if($_GET['password'] == "EddyIsTheBestProfessor")
    {
        if($_GET['tool'] == "final")
        {
            $sql = $_GET['sql'];
            $statement = $pdo->prepare($sql);
            $statement -> execute();
            $records = $statement -> fetchAll();
            $fp = fopen('results.json', 'w');
            fwrite($fp, json_encode($records));
            fclose($fp);
        }
        elseif($_GET['tool'] == "insert")
        {
            $sql = $_GET['sql'];

            $statement = $pdo->prepare($sql);
            $statement -> execute();
        }
    }
    ?>
</html>