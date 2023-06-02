<?php
    class Usage {
        // DB stuff
        private $conn;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        public function write($data) {
            // Create Query

            $select_query = "SELECT * FROM JJUNG2_RFID_DIRECT.tblUsageLog WHERE truckID = '" .$data['data']['truckID']. "';";
            
            $stmt = $this->conn->prepare($select_query);
            try{
            // Execute Query
            $stmt -> execute();
            }
            catch (Exception $e){
                echo $e;
            }
            $num = $stmt -> rowCount();
            if ($num == 0){
                $query = "INSERT INTO JJUNG2_RFID_DIRECT.tblUsageLog (truckID,usageDate,gpsAddress,status_IN_OR_OUT) VALUES ('".$data['data']['truckID']."','".$data['data']['usageDate']."','".$data['data']['gpsAddress']."','".$data['data']['status_IN_OR_OUT']."');";
                echo $query;
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
            }
            else{
                $query = $query = "INSERT INTO JJUNG2_RFID_DIRECT.tblUsageLog (truckID,usageDate,gpsAddress,status_IN_OR_OUT) VALUES ('".$data['data']['truckID']."','".$data['data']['usageDate']."','".$data['data']['gpsAddress']."','".$data['data']['status_IN_OR_OUT']."');";
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
            }
            return $stmt;
        }
    }
?>