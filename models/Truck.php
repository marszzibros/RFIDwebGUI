<?php
    class Truck {
        // DB stuff
        private $conn;
        private $table = 'tblTruck';

        // tblTruck properties
        public $truckID;
        public $license_plate;
        public $make_model;
        public $manufactureYear;
        public $driverID;
        public $acqusition_date;
        public $deployment_date;
        
        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }
        // convert tagID -> assetID
        public function convert($tagID) {
            $query = "SELECT JJUNG2_RFID_DIRECT.tblRFIDTag.truckID FROM JJUNG2_RFID_DIRECT.tblRFIDTag WHERE JJUNG2_RFID_DIRECT.tblRFIDTag.tagID = '" . $tagID . "';";
            $stmt = $this->conn->prepare($query);
            try{
            // Execute Query
            $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            $num = $stmt -> rowCount();
            if ($num > 0) {
                $result = "";
                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $result = $truckID;
                }
                return $result ;
            }
            else return "";
        }
        // update
        public function update($data) {
            $query = "UPDATE JJUNG2_RFID_DIRECT.tblTruck 
            SET JJUNG2_RFID_DIRECT.tblTruck.license_plate = '" . $data['data']['license_plate'] . "', 
            JJUNG2_RFID_DIRECT.tblTruck.make_model = '" . $data['data']['make_model'] . "',
            JJUNG2_RFID_DIRECT.tblTruck.manufactureYear = '" . $data['data']['manufactureYear'] . "', 
            JJUNG2_RFID_DIRECT.tblTruck.driverID = '" . $data['data']['driverID'] . "', 
            JJUNG2_RFID_DIRECT.tblTruck.acquisition_date = '" . $data['data']['acquisition_date'] . "', 
            JJUNG2_RFID_DIRECT.tblTruck.deployment_date = '" . $data['data']['deployment_date'] . "'  
            WHERE JJUNG2_RFID_DIRECT.tblTruck.truckID = '" . $data['data']['truckID'] . "';";
            $stmt = $this->conn->prepare($query);
            echo $query;
            try{
            // Execute Query
            $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            $num = $stmt -> rowCount();
            
            if($num > 0) return true;
            else return false;
        }
        // write
        public function write($data) {
            // Create Query
            $queryTruck = "INSERT INTO JJUNG2_RFID_DIRECT.tblTruck
            (truckID, license_plate, make_model,
            manufactureYear,driverID,acquisition_date, deployment_date) 
            VALUES ('".$data['data']['truckID']."','".$data['data']['license_plate']."','".$data['data']['make_model'].
            "','".$data['data']['manufactureYear']."','".$data['data']['driverID']."','".$data['data']['acquisition_date'].
            "','".$data['data']['deployment_date']."');";
            
            echo "queryTruck";
            $queryRFIDTag = "INSERT INTO JJUNG2_RFID_DIRECT.tblRFIDTag 
            (tagID, manufactureDate, dateEntered, truckID, installationDate)
            VALUES ('".$data['data']['tagID']."','".$data['data']['manufactureDate'].
            "','".$data['data']['dateEntered']."','".$data['data']['truckID']."','".$data['data']['installationDate']."');";

            echo "queryRFIDTag";
            $stmt = $this->conn->prepare($queryTruck);
            echo $queryTruck;
            try{
            // Execute Query
                $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }

            $stmt = $this->conn->prepare($queryRFIDTag);
            try{
            // Execute Query
                $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }


            
            return $stmt;
        }
        // check if there is a data in database
        public function checkUpdate($tagID) {
            $query = "SELECT JJUNG2_RFID_DIRECT.tblRFIDTag.truckID FROM JJUNG2_RFID_DIRECT.tblRFIDTag WHERE JJUNG2_RFID_DIRECT.tblRFIDTag.tagID = '" . $tagID . "';";
            $stmt = $this->conn->prepare($query);
            try{
            // Execute Query
            $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            $num = $stmt -> rowCount();
            $row = array();
            
            if($num > 0) {
    
                $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                extract($row);
            }
            return $row['truckID'];
        }
        // Get Read
        public function read($tagName) {
            if($tagName != "all"){
                $query = 'SELECT JJUNG2_RFID_DIRECT.tblTruck.truckID, 
                JJUNG2_RFID_DIRECT.tblTruck.license_plate, 
                JJUNG2_RFID_DIRECT.tblTruck.make_model, 
                JJUNG2_RFID_DIRECT.tblTruck.manufactureYear, 
                JJUNG2_RFID_DIRECT.tblTruck.driverID, 
                JJUNG2_RFID_DIRECT.tblTruck.acquisition_date, 
                JJUNG2_RFID_DIRECT.tblTruck.deployment_date, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.tagID, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.manufactureDate, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.dateEntered, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.installationDate 
                FROM JJUNG2_RFID_DIRECT.tblTruck, JJUNG2_RFID_DIRECT.tblRFIDTag 
                WHERE JJUNG2_RFID_DIRECT.tblRFIDTag.tagID = "' . $tagName .'" AND JJUNG2_RFID_DIRECT.tblRFIDTag.truckID = tblTruck.truckID;';
            }
            else if($tagName == "all"){
                $query = 'SELECT JJUNG2_RFID_DIRECT.tblTruck.truckID, 
                JJUNG2_RFID_DIRECT.tblTruck.license_plate, 
                JJUNG2_RFID_DIRECT.tblTruck.make_model, 
                JJUNG2_RFID_DIRECT.tblTruck.manufactureYear, 
                JJUNG2_RFID_DIRECT.tblTruck.driverID, 
                JJUNG2_RFID_DIRECT.tblTruck.acquisition_date, 
                JJUNG2_RFID_DIRECT.tblTruck.deployment_date, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.tagID, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.manufactureDate, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.dateEntered, 
                JJUNG2_RFID_DIRECT.tblRFIDTag.installationDate 
                FROM JJUNG2_RFID_DIRECT.tblTruck, JJUNG2_RFID_DIRECT.tblRFIDTag 
                WHERE JJUNG2_RFID_DIRECT.tblRFIDTag.truckID = tblTruck.truckID;';
            }

            // Create Query
            $stmt = $this->conn->prepare($query);
            try{
            // Execute Query
            $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            return $stmt;
        }
    }
?>