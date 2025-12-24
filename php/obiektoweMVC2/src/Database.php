<?php
namespace Src;

use MongoDB\Client;

class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $client = new Client(
                    "mongodb://localhost:27017/wai", 
                    [
                        'username' => 'wai_web',
                        'password' => 'w@i_w3b',
                    ]
                );
                self::$instance = $client->wai;
            } catch (\Exception $e) {
                die("Błąd połączenia z bazą: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}