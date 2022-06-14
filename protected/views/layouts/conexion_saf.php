<?php
$dbstr ="(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST =172.18.1.145)(PORT = 1521))(CONNECT_DATA = (SERVICE_NAME = safstby)))"; 
$conn = oci_connect('regionviii_info','regionviii_info',$dbstr,'UTF8');
?>