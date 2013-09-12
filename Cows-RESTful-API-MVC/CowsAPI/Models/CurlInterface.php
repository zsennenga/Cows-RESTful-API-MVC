<?php

namespace CowsAPI\Models;

/**
 * Interface to perform web requests
 * @author its-zach
 *
 */
interface CurlInterface {
    public function setOption($name, $value);
    public function execute();
    public function getInfo($name);
    public function close();
}
?>