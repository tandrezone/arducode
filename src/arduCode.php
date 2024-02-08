<?php

const STUBS_FOLDER = "src/stubs/";
const BUILD_FOLDER = "src/build/";

require("Sensor.php");
$sensors[] = new Sensor("led", 13,"OUTPUT","LED");
$sensors[] = new Sensor("temphum", 2,"OUTPUT","DHT11");

$header = file_get_contents(STUBS_FOLDER . "header.ino") . PHP_EOL. PHP_EOL;
$setupCode =  file_get_contents(STUBS_FOLDER . "setup.ino") . PHP_EOL. PHP_EOL ;
$loopCode = file_get_contents(STUBS_FOLDER . "loop.ino") . PHP_EOL. PHP_EOL;
$idx = 1;
foreach($sensors as $sensor) {
        $fromstubHeader = file_get_contents(STUBS_FOLDER."/".$sensor->getType()."/". "header.ino");
        $codeHeader = transcode($fromstubHeader,$sensor->getName(), $sensor->getPort(),$sensor->getMode());
        if($idx < sizeof($sensors)){
            $codeHeader = $codeHeader.PHP_EOL;
        }
        $header = $header.$codeHeader . PHP_EOL;

        $fromstubSetup = file_get_contents(STUBS_FOLDER."/".$sensor->getType()."/". "setup.ino");
        $codeSetup = transcode($fromstubSetup,$sensor->getName(), $sensor->getPort(),$sensor->getMode());
        if($idx < sizeof($sensors)){
            $codeSetup = $codeSetup.PHP_EOL;
        }
        $setupCode = $setupCode.$codeSetup . PHP_EOL;

        $fromstubLoop = file_get_contents(STUBS_FOLDER."/".$sensor->getType()."/". "loop.ino");
        $codeLoop = transcode($fromstubLoop,$sensor->getName(), $sensor->getPort(),$sensor->getMode());
        if($idx < sizeof($sensors)){
            $codeLoop = $codeLoop.PHP_EOL;
        }
        $loopCode = $loopCode.$codeLoop . PHP_EOL; 
        $idx++;
}


$setup = "void setup() ".PHP_EOL."{" . PHP_EOL . $setupCode  . "}" . PHP_EOL;
$loop = "void loop() ".PHP_EOL."{" . PHP_EOL .$loopCode  . "}";

$file = $header . "" . $setup . "" . $loop;


$file = format($file);

file_put_contents(BUILD_FOLDER . "main.ino", $file);

function transcode($code,$name,$port,$mode) {
    $code = str_replace("% PIN_NAME %", $name, $code);
    $code = str_replace("% PIN_PORT %", $port, $code);
    $code = str_replace("% PIN_MODE %", $mode, $code);
    return $code;
}

function format($code) {
    $level = 0;
    $lines = explode(PHP_EOL, $code);
    $formatedCode = "";
    foreach($lines as $line){
        $line = str_replace("\t", "", $line);
        if(str_contains($line, "}")) {
            $level--;
        };
        $tabs = "";
        for($i = 0; $i< $level;$i++) {
            $tabs = $tabs."\t"; 
        }
        echo $level."               ".$line.PHP_EOL;
        $formatedCode = $formatedCode.$tabs.$line.PHP_EOL;
        if(str_contains($line, "{")) {
            $level++;
        };

    }
    return $formatedCode;
}