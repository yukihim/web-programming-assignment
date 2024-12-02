<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'Database.php';

class Model {
    protected $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }
}
