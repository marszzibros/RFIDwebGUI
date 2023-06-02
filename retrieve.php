<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assets Managementr</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="custom.css?version=<?php print time(); ?>" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<?php
include 'connect-DB.php';
?>
<body>
<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dataValid = False;
$userID = '';
$IDcheck ='';
$parts = array();

$reason = array();

$count  = 0;

function getData($field) 
{
    if (!isset($_POST[$field])) $data ="";
    else{
        $data = trim($_POST[$field]);
        $data = htmlspecialchars($data);
    }
    return $data;
}
function verifyAlphaNum($testString) {
    // Check for letters, numbers and dash, period, space and single quote only.
    // added & ; and # as a single quote sanitized with html entities will have 
    // this in it bob's will be come bob&#039;s
    return (preg_match ("/^([[:alnum:]]|-|\.| |\'|&|;|#)+$/", $testString));
}
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>

<script>

$(function() {
    if (!Modernizr.inputtypes['date']) {
        $('input[type=date]').datepicker({ 
            dateFormat: 'yy-mm-dd'
        });
    }
});
</script>
    <main role="main" class="container">
        <article>
            <h2>Your Bike Record is Here!</h2>
            <section id = "information">
                <?php
                if($_SERVER["REQUEST_METHOD"] == "GET")
                {
                    $dataValid = true;
                    
                    $userID = getData("txtIDsubmit");
                    $sql = "SELECT * FROM  WHERE raspID = ?;";
                    $statement = $pdo->prepare($sql);
                    $params = array($userID);
                    $statement->execute($params);
                    $IDcheck = $statement -> fetchAll();
    
                    $startDate = getData("datStart");
                    $endDate = getData("datEnd");
                    $radiobutton = getData("radParts");
    
                    if ($userID == "")
                    {
                        $reason[$count] = '<p class = "message"> Please enter your userID. </p>';
                        $count++;
                        $dataValid = false;
                    }
                    elseif (!verifyAlphaNum($userID))
                    {
                        $reason[$count] = '<p class = "message">Your User id appears to be incorrect.</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    elseif (count($IDcheck) == 0)
                    {
                        $reason[$count] = '<p class ="message">'.$userID.' is not on the database!</p>';
                        $count++;
                        $dataValid = false;
                    }
    
                    if ($startDate == "")
                    {
                        $reason[$count] = '<p class = "message">Please Type Your starting day</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    elseif ($startDate > date('Y-m-d') OR $startDate < date('1900-01-01'))
                    {
                        $reason[$count] = '<p class = "message">Your starting day appears to be invalid</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    //https://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format
                    elseif(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $startDate))
                    {
                        $reason[$count] = '<p class = "message">Your starting day appears to be invalid</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    if ($endDate == "")
                    {
                        $reason[$count] = '<p class = "message">Please Type Your ending day</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    elseif ($endDate > date('Y-m-d') OR $endDate < date('1900-01-01'))
                    {
                        $reason[$count] = '<p class = "message">Your ending day appears to be invalid</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    //https://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format
                    elseif(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $endDate))
                    {
                        $reason[$count] = '<p class = "message">Your ending day appears to be invalid</p>';
                        $count++;    
                        $dataValid = false;
                    }
                    if(date($endDate) < date($startDate))
                    {
                        $reason[$count] = '<p class = "message">Your starting day should not be bigger than the ending day</p>';
                        $count++;    
                        $dataValid = false;
                    }
                }
                ?>
                <h3>Type Your ID and Periods</h3>
                <form action = "#" method = "POST" id = "frmTable">
                    <fieldset id = "IDsubmit">
                        <p>
                            <label for="txtIDsubmit" class="require">User ID: </label>
                            <input type="text" name="txtIDsubmit" id="txtIDsubmit" value = "<?php print $userID;?>" required>
                        </p>
                    </fieldset>
                    <fieldset id = "SortMethods">
    
                        <p id="startDate">
                            <label for="datStart" class="require">Starting date:</label>
                            <input type="date" name="datStart" id="datStart" min = "1900-01-01" max = "<?php print date('Y-m-d');?>" value = "<?php print $startDate;?>" required>
                        </p>
                        <p id="endDate">
                            <label for="datEnd" class="require">Ending date:</label>
                            <input type="date" name="datEnd" id="datEnd" min = "1900-01-01" max = "<?php print date('Y-m-d');?>" value = "<?php print $endDate;?>" required>
                        </p>       
    
    
                    </fieldset>
                    <fieldset id="submitIDandPeriods">
                        <input type="submit" name="btnTable" value="submit">
                    </fieldset>
                </form>
            </section>
            <?php
            if($dataValid)
            {
                $sql = 'SELECT * FROM tblFinal
                WHERE raspID = ? AND travel >= ? AND travel <= ? ORDER BY travel DESC';
                $statement = $pdo->prepare($sql);
                $date = date_create($endDate);
                date_add($date, date_interval_create_from_date_string('1 days'));
                $params = array($userID,$startDate,date_format($date, 'Y-m-d'));
                $statement->execute($params);
        
                $records = $statement->fetchAll();
    
                if(count($records) >= 1)
                {
                    print '<section id="tableGenerator">';
                    print '<h3>Records of '.$startDate.' ~ '.$endDate.'</h3>';
                    print '<table id="bikeRecord">';
                    print '<tr><th id="date"> Date </th><th> duration </th><th> distance </th><th> avg speed </th><th> avg Temp </th><th> avg Humidity </th><th> avg PM 2.5 </th><th> route </th></tr>';
                    foreach ($records as $record)
                    {
                        print('<tr>');
                        print PHP_EOL;
                        print('<td>'.$record['travel'].'</td>');
                        print PHP_EOL;    
                        $duration = round($record['duration'],2);
                        print('<td>'.$duration.'mins</td>');
                        print PHP_EOL;
                        $distance = round($record['distance'],2);
                        print('<td>'.$distance.'km</td>');
                        print PHP_EOL;
                        $avgSpeed = round($record['avgSpeed'],2);
                        print('<td>'.$avgSpeed.'km/h</td>');
                        print PHP_EOL;
                        $avgTemp = round($record['avgTemp'],2);
                        print('<td>'.$avgTemp.' C</td>');
                        print PHP_EOL;
                        $avgHumid = round($record['avgHumid'],2);
                        print('<td>'.$avgHumid.'%</td>');
                        print PHP_EOL;
                        $avgAir = round($record['avgAirQual'],2);
                        print('<td>'.$avgAir.'</td>');
                        print PHP_EOL;
                        print('<td><a href = '.$record['APIlink'].'>CLICK HERE TO VIEW</td>');
                        print PHP_EOL;
                        print('</tr>');
                        print PHP_EOL;
                    }
                    print '</table>';
                    print '</section>';
                }
            }
            else
            {
                print '<aside>';
                print '<ul>';
                for ($i = 0; $i < count($reason);$i++)print '<li>'.$reason[$i].'</li>';
                print '</ul>';
                print '</aside>';
            }
            ?>
        </article>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
