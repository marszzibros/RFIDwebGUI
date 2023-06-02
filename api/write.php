<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    include_once '../config/Database.php';
    include_once '../models/Truck.php';
    include_once '../models/Inspection.php';
    include_once '../models/Usage.php';
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();
    // Creating messageBoard instance

    $truck = new Truck($db);
    // POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // tblInspection
        
        if($_POST['request'] == "inspection") {
            if(!empty($_POST['tagID'])){
                $truckID = $truck -> convert($_POST['tagID']);
                $inspection = new Inspection($db);
                $inspectionData = array();
                $inspectionData['data'] = array();
                $inspectionData['data']['truckID'] = $truckID;
                $inspectionData['data']['inspectionDate'] = date("Y-m-d H:i:s");
                $inspectionData['data']['inspectorName'] = $_POST['inspectorName'];
                $inspectionData['data']['inspectionResult'] = $_POST['inspectionResult'];
                $inspection -> write($inspectionData);
            }
        }
        // tblUsage
        else if($_POST['request'] == "usage") {
            if(!empty($_POST['tagID'])){
                $truckID = $truck -> convert($_POST['tagID']);
                $usage = new Usage($db);
                $usageData = array();
                $usageData['data'] = array();
                $usageData['data']['truckID'] = $truckID;
                $usageData['data']['usageDate'] = date("Y-m-d H:i:s");
                $usageData['data']['gpsAddress'] = $_POST['gpsAddress'];
                $usageData['data']['status_IN_OR_OUT'] = $_POST['status_IN_OR_OUT'];
                $usage -> write($usageData);
            }
            
        }
        else if($_POST['request'] == "truck") {
            if(!empty($_POST['tagID'])){
                $truckData = array();
                $truckData['data'] = array();
                $truckData['data']['truckID'] = $_POST['truckID'];
                $truckData['data']['license_plate'] = $_POST['license_plate'];
                $truckData['data']['make_model'] = $_POST['make_model'];
                $truckData['data']['manufactureYear'] = $_POST['manufactureYear'];
                $truckData['data']['driverID'] = $_POST['driverID'];
                $truckData['data']['acquisition_date'] = $_POST['acquisition_date'];
                $truckData['data']['deployment_date'] = $_POST['deployment_date'];

                $truckData['data']['tagID'] = $_POST['tagID'];
                $truckData['data']['manufactureDate'] = $_POST['manufactureDate'];
                $truckData['data']['dateEntered'] = $_POST['dateEntered'];
                $truckData['data']['installationDate'] = $_POST['installationDate'];
                
                // check if it is a new data
                // not new
                if($truck -> checkUpdate($_POST['tagID'])) { 

                    $truck -> update($truckData);
                }
                // 
                else {
                    $truck -> write($truckData);
                }
            }
            else {
                echo json_encode(
                    array('message' => 'Invalid Request')
                );
            }
        }
        // wrong request
        else {
            echo json_encode(
                array('message' => 'Invalid Request')
            );
        }
    }
    // not post
    else {
        echo json_encode(
            array('message' => 'Invalid Request')
        );
    }
?>