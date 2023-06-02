<?php
    class Inspection {
        // DB stuff
        private $conn;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        public function write($data) {
            // Create Query
            $query = "INSERT INTO JJUNG2_RFID_DIRECT.tblInspectionLog (truckID,inspectionDate,inspectorName,inspectionResult) VALUES ('".$data['data']['truckID']."','".$data['data']['inspectionDate']."','".$data['data']['inspectorName']."','".$data['data']['inspectionResult']."');";
            $stmt = $this->conn->prepare($query);
            try{
            // Execute Query
                $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            if ($stmt) {
                echo json_encode(
                    array('message' => 'success')
                );
            }
            else {
                echo json_encode(
                    array('message' => 'failed')
                );
            }
            return $stmt;
        }
    }
?>