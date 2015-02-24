<?php
session_start();
$result =  json_decode(file_get_contents("php://input"));
require  __DIR__ .'/vendor/autoload.php';
require 'config.php';
require 'ses.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\KEYType;
use Aws\DynamoDb\Enum\AttributeAction;


$tableName = 'bookmark_user';
$dynamodb = DynamoDbClient::factory(array('region' => $region));

$client = DynamoDbClient::factory($config);
$ses = new SimpleEmailService($config['key'],$config['secret']);

$action = $result->actionType;



switch($action){
	
case 'login':

$response = $client->getItem(array(
                      "TableName" =>$tableName,
                                     "Key" => array(
                                             "bookmark_user_email" => array( Type::STRING => $result->userEmail),
                                             "bookmark_user_password"  => array( Type::STRING => sha1(md5($result->userPassword)))
                                            )
                                        ));
				 if(count($response)>0){ 
				 
				 			
				 			$bookmark_user_auth_token_list = array();
				 			$tokenValue = md5(date('Y-m-d').$response['Item']['bookmark_user_fullname']['S']);
							array_push($bookmark_user_auth_token_list,$tokenValue);
							
							
							$response = $client->updateItem(array(
							    'TableName' => $tableName,
							    'Key' => array(
							        'bookmark_user_email' => array(Type::STRING => $result->userEmail),
							        'bookmark_user_password' => array(Type::STRING => sha1(md5($result->userPassword)))
							    ),
							    'AttributeUpdates' => array(
							        'bookmark_user_auth_token' => array(
							            'Value' => array(Type::STRING_SET => $bookmark_user_auth_token_list),
							            'Action' => AttributeAction::PUT)
							    ),
							    'ReturnValues' => 'ALL_NEW'
							));
				 				
				             $userEmail =$result->userEmail;
				             echo json_encode(array('message'=>'success',
				             						'userEmail'=>trim($userEmail),
				             						'token'=>$bookmark_user_auth_token_list));
				   }
				   else{
				            echo json_encode(array('message'=>'error'));
				   }
  break;
  case 'signup':

		print_r($_FILES);
		print_r($result);
  		 $response = $client->putItem(array(
                               "TableName" => $tableName,
                                     "Item" => $client->formatAttributes(array(
                                     "bookmark_user_fullname" => $result->bookmark_user_firstname." ".$result->bookmark_user_lastname,
                                     "bookmark_user_email" => $result->bookmark_user_email,
                                     "bookmark_user_password" => sha1(md5($result->bookmark_user_password)),
                                     "bookmark_user_created_date" => date('D-M-Y h:i:s'),
                                     "bookmark_user_updated_date" => date('D-M-Y h:i:s')
                                             )),
          "ReturnConsumedCapacity" => 'TOTAL'
          ));
         if(count($response)>0){ 
        					
        					echo json_encode(array('message'=>'success')); 
        					$mail = new SimpleEmailServiceMessage();
        					$mail->addTo($result->bookmark_user_email);
  							$mail->setFrom($adminEmail);
  							$mail->setSubject('Bookmark registration completed');
  							$mail->setMessageFromString('Your bookmark registration completed. You can login with your email id and password');
							 
        		}
          else{
		  	    echo json_encode(array('message'=>'error')); 
		  }		
 break;
  
 default;				   
				   
	}			 