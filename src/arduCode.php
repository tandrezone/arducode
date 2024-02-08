<?php
require("Sensor.php");

const STUBS_FOLDER = "src/stubs/";
const BUILD_FOLDER = "src/build/";

$sensors[] = new Sensor("led", 13, "OUTPUT", "LED");
$sensors[] = new Sensor("temphum", 2, "OUTPUT", "DHT11");

$header = file_get_contents(STUBS_FOLDER . "header.ino");
if (!empty($header)) {
    $header = $header . PHP_EOL . PHP_EOL;
}
$setupCode = file_get_contents(STUBS_FOLDER . "setup.ino") . PHP_EOL . PHP_EOL;
$loopCode = file_get_contents(STUBS_FOLDER . "loop.ino") . PHP_EOL . PHP_EOL;
$idx = 1;
$stubs = ['header', 'setup', 'loop'];
$code = [];
foreach ($stubs as $stub) {
    $code[$stub] = "";
}
foreach ($sensors as $sensor) {
    foreach ($stubs as $stub) {
        $auxCode1 = file_get_contents(STUBS_FOLDER . "/" . $sensor->getType() . "/" . $stub . ".ino");
        $auxCode2 = transcode($auxCode1, $sensor->getName(), $sensor->getPort(), $sensor->getMode());
        $code[$stub] = $code[$stub] . $auxCode2 . PHP_EOL;
    }
}

$setup = "void setup() " . PHP_EOL . "{" . PHP_EOL . $code['setup'] . "}" . PHP_EOL;
$loop = "void loop() " . PHP_EOL . "{" . PHP_EOL . $code['loop'] . "}";

$file = $code['header'] . PHP_EOL . $setup . PHP_EOL . $loop;


$file = format($file);

file_put_contents(BUILD_FOLDER . "main.ino", $file);

function transcode($code, $name, $port, $mode)
{
    $code = str_replace("% PIN_NAME %", $name, $code);
    $code = str_replace("% PIN_PORT %", $port, $code);
    $code = str_replace("% PIN_MODE %", $mode, $code);
    return $code;
}

function format($code)
{
    $level = 0;
    $lines = explode(PHP_EOL, $code);
    $formatedCode = "";
    foreach ($lines as $line) {
        $line = str_replace("\t", "", $line);
        $line = str_replace("  ", "", $line);
        if (str_contains($line, "}")) {
            $level--;
        }

        $tabs = "";
        for ($i = 0; $i < $level; $i++) {
            $tabs = $tabs . "\t";
        }
        echo $level . "               " . $line . PHP_EOL;
        $formatedCode = $formatedCode . $tabs . $line . PHP_EOL;
        if (str_contains($line, "{")) {
            $level++;
        }
        ;

    }
    return $formatedCode;
}