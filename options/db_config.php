<?php
    class DbConfig {
        private static $db_name = "tarea2";
        private static $db_user = "root";
        private static $db_pass = "";
        private static $db_host = "localhost";

        //private static $db_user = 'cc500218_u';
        //private static $db_pass = 'arutrumn';
        //private static $db_name = 'cc500218_db';

        public static function getConnection() {
            $mysqli = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);

            $return_array = array("connection" => true, "message" => "", "db" => $mysqli);

            if ($mysqli->connect_error) {
                $return_array["connection"] = false;
                $return_array["message"] = "0";
                return $return_array;
            }

            $enc = $mysqli->set_charset("utf8");

            if (!$enc) {
                $return_array["connection"] = false;
                $return_array["message"] = "1";
                return $return_array;
            }

            return $return_array;
        }

    }

?>