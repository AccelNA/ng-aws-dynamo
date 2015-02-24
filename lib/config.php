<?php

// Include the SDK using the Composer autoloader
require  __DIR__ .'/vendor/autoload.php';
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;


 $config = array(
                                 'key'    => 'YOUR KEY',
                                 'secret' => 'YOUR SECRET KEY',
                                 'profile' => 'default',
                                 'region' => Region::US_EAST_1 
                               );
$region      =  'us-east-1'; 
$bucketName  =  'your bucket name';                           

$adminEmail ='demo@email.com';
 
?>