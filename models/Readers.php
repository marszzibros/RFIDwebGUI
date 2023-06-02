<?php
    class Readers {
        // DB stuff
        private $conn;
        private $table = 'tblReaders';

        // tblReaders properties
        public $num_id;
        public $readerID;

        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Readers
        public function read() {
            // Create Query
            $query = 'SELECT * FROM JJUNG2_RFID_DIRECT.' . $this -> table;
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