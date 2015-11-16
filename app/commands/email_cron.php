#!/usr/bin/php
include_once();
<?
$db = mysql_pconnect('localhost','filbadco_rafael','TR7!2BnW@3CE');
mysql_select_db('filbadco_badminton', $db);


$sql_select = "SELECT * FROM email ORDER BY `id` ASC LIMIT 1";
$result = mysql_fetch_assoc(mysql_query($sql_select)); 

mail('paengski13@gmail.com','Test Invite email','test',"From: webmaster@example.com");
print_r($result);