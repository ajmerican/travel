<?php
require_once "utils.php";

class FileDB
{
    const DB_PATH = "db/";
    private $path;
    private $data = array();

    private static $entities = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public static function entity($name)
    {
        $p = dirname(__FILE__) .'/../../'.self::DB_PATH . $name . ".db";
        if (!file_exists($p)) {
            if (!file_exists(dirname($p))) {
                mkdir(dirname($p), 0755, true);
            }
            $h = touch($p);
        }
        if(!empty(self::$entities[$name])) {
            $db = self::$entities[$name];
        }
        else {
            $db = new FileDB($p);
            $db->readData();
            self::$entities[$name] = $db;
        }

        return $db;
    }

    public function get($key)
    {
        return $this->data[$key];
    }

    public function all()
    {
        return $this->data;
    }

    public function filter($key, $value){
        $col = new Collection();
        foreach($this->data as $row){
            if(isset($row[$key]) && Utils::startsWith($row[$key], $value)){
                $col->addItem($row);
            }
        }

        return $col->items;
    }

    public function filterOne($key, $value){
        foreach($this->data as $row){
            if(isset($row[$key]) && $row[$key]===$value){
                return $row;
            }
        }

        return null;
    }

    public function search($key, $value){

        $col = new Collection();
        foreach($this->data as $row){
            if(isset($row[$key]) && Utils::in($row[$key], $value)){
                $col->addItem($row);
            }
        }

        return $col->items;
    }

    public function put($key, $value)
    {
        $this->data[$key] = $value;
        $this->writeData();
    }

    private function readData()
    {
        $this->data = json_decode(file_get_contents($this->path), true);
        switch (json_last_error()) {
          case JSON_ERROR_NONE:
              //echo ' - No errors';
          break;
          case JSON_ERROR_DEPTH:
              echo ' - Maximum stack depth exceeded';
          break;
          case JSON_ERROR_STATE_MISMATCH:
              echo ' - Underflow or the modes mismatch';
          break;
          case JSON_ERROR_CTRL_CHAR:
              echo ' - Unexpected control character found';
          break;
          case JSON_ERROR_SYNTAX:
              echo ' - Syntax error, malformed JSON';
          break;
          case JSON_ERROR_UTF8:
              echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
          break;
          default:
              echo ' - Unknown error';
          break;
      }
    }

    private function writeData()
    {
        file_put_contents($this->path, json_encode($this->data));
    }
}
