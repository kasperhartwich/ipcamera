<?php
include('lib/IPCamera.php');

$ipcamera = new IPCamera('192.168.1.10', 80, 'admin', '');

$response = $ipcamera->status();

var_dump($response);
