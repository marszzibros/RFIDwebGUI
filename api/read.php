<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    include_once '../config/Database.php';
    include_once '../models/Readers.php';
    include_once '../models/Truck.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // for READER
    if (isset($_GET["request"])&& trim($_GET["request"]) == "reader") {
        // Instantiate reader object
        $reader = new Readers($db);

        // readers query
        $result = $reader->read();

        // Get Row count
        $num = $result -> rowCount();

        // Check if any readers
        if($num > 0) {
            // reader array
            $readers_arr = array();
            $readers_arr['data'] = array();

            while($row = $result -> fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $reader_item = array(
                    'num_id' => $num_id,
                    'readerID' => $readerID
                );
                try{
                // Push to "data"
                    array_push($readers_arr['data'], $reader_item);
                }
                catch(Exception $e){
                    echo $e;
                }
            }

            // Turn to JSON & output
            echo json_encode($readers_arr);
        }
        else{
            // no readers
            echo json_encode(
                array('message' => 'No information found')
            );
        }
    }
    // to retrieve tag data
    else if (isset($_GET["request"])&& trim($_GET["request"]) == "tag"){
        if (isset($_GET["id"])&& !empty(trim($_GET["id"]))) {
            // Instantiate reader object
            $tagID = $_GET["id"];
            $truck = new Truck($db);

            // readers query
            $result = $truck->read($tagID);

            // Get Row count
            $num = $result -> rowCount();

            $tags_arr = array();
            $tags_arr['data'] = array();
            // Check if any readers
            if($num > 0) {
                // reader array
                while($row = $result -> fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $tag_item = array(
                        'truckID' => $truckID,
                        'license_plate' => $license_plate,
                        'make_model' => $make_model,
                        'manufactureYear' => $manufactureYear,
                        'driverID' => $driverID,
                        'acquisition_date' => $acquisition_date,
                        'deployment_date' => $deployment_date,
                        'tagID' => $tagID,
                        'manufactureDate' => $manufactureDate,
                        'dateEntered' => $dateEntered,
                        'installationDate' => $installationDate,
                        'message' => 'found'
                    );
                    try{
                    // Push to "data"
                        array_push($tags_arr['data'], $tag_item);
                    }
                    catch(Exception $e){
                        echo $e;
                    }
                }
            }
            else{
                $tag_item = array(
                    'message' => 'not found'
                );
                try{
                    // Push to "data"
                        array_push($tags_arr['data'], $tag_item);
                }
                catch(Exception $e){
                    echo $e;
                }
            }
            echo json_encode($tags_arr);
        }
        else{
            // no readers
            echo json_encode(
                array('message' => 'error: no id specified')
            );
        }
    }
    else{
        // no readers
        echo json_encode(
            array('message' => 'Invalid Request')
        );
    }

?>
