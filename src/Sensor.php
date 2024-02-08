<?php 
class Sensor {
    public String $name;
    public int $port;
    public String $mode;
    public String $type;
    public function __construct($name, $port,$mode,$type) {
$this->name = $name;
$this->port = $port;
$this->mode = $mode;
$this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function getName() {
        return $this->name;
    }

    public function getPort() {
        return $this->port;
    }

    public function getMode(){
        return $this->mode;
    }
}