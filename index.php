<?php
$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
$path_parts = pathinfo($phpSelf)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assets Management</title>
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
$startDate = '';
$endDate = '';
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
            <h2>Your Record is Here!</h2>
            <section id = "type_id_date">
                <?php
                if($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    $dataValid = true;
                    
    
                    $startDate = getData("datStart");
                    $endDate = getData("datEnd");
                    $radiobutton = getData("radParts");

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
                <h3>Type Your truck ID and Periods</h3>
                <form action = "#" method = "POST" id = "frmTable">
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
                $sql = 'SELECT * FROM tblUsageLog
                WHERE usageDate >= ? AND usageDate <= ? ORDER BY usageDate DESC';
                $statement = $pdo->prepare($sql);
                $date = date_create($endDate);
                date_add($date, date_interval_create_from_date_string('1 days'));
                $params = array($startDate,date_format($date, 'Y-m-d'));
                $statement->execute($params);
        
                $records = $statement->fetchAll();
    
                if(count($records) >= 1)
                {
                    print '<section id="tableGenerator">';
                    print '<h3>Records of '.$startDate.' ~ '.$endDate.'</h3>';
                    print '<table id="TruckRecord">';
                    print '<tr><th id="truckID"> ID </th><th id="date"> Date </th><th> gpsAddress </th><th> status_IN_OR_OUT </th></tr>';
                    foreach ($records as $record)
                    {
                        print('<tr>');
                        print PHP_EOL;
                        print('<td><a href = "https://jjung2.w3.uvm.edu/sample/REST_API/retrieve.php$id='.$record['truckID'].'">'.$record['truckID'].'</td>');
                        print PHP_EOL;
                        print('<td>'.$record['usageDate'].'</td>');
                        print PHP_EOL;    
                        print('<td>'.$record['gpsAddress'].'</td>');
                        print PHP_EOL;
                        print('<td>'.$record['status_IN_OR_OUT'].'</td>');
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
