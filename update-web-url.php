<?php

$xml = simplexml_load_file('app/etc/local.xml');
$host = $xml->global->resources->default_setup->connection->host;
$username = $xml->global->resources->default_setup->connection->username;
$password = $xml->global->resources->default_setup->connection->password;
$dbname = $xml->global->resources->default_setup->connection->dbname;

$table_prefix = $xml->global->resources->db->table_prefix;

$link = mysqli_connect($host, $username, $password, $dbname);




$server =  $_SERVER['HTTP_HOST'];
$serverName = $_SERVER['SERVER_NAME'];
$self = $_SERVER['PHP_SELF'];
$self = explode("/", $self);
$self = $self[1];


$url = 'http://'.$server.'/';

$sql = "UPDATE ".$table_prefix."core_config_data SET `value` = '{$url}' WHERE path = 'web/unsecure/base_url' OR path = 'web/secure/base_url'";


if($sql != ''){
    $_res = mysqli_query($link,$sql);
    if($_res) echo "Done!<br>";
}



