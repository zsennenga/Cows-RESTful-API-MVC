<?php

namespace CowsAPI\Models;

interface CurlInterface {
    public function setOption($name, $value);
    public function execute();
    public function getInfo($name);
    public function close();
}
?>