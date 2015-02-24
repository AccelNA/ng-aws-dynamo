<?php
session_start();
$result =  json_decode(file_get_contents("php://input"));
require  __DIR__ .'/vendor/autoload.php';
require 'config.php';


use Aws\DynamoDb\DynamoDbClient;
use Aws\Common\Enum\Region;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\KEYType;
use Aws\DynamoDb\Enum\AttributeAction;

$tableName = 'bookmark_resources_group';
$dynamodb = DynamoDbClient::factory(array('region' => $region));
$instanceId = file_get_contents("http://169.254.169.254/latest/meta-data/instance-id");
$client = DynamoDbClient::factory($config);

$action = $result->actionType;

$userEmail = trim($_COOKIE['userEmail']);
$userEmail = trim($userEmail,'"');


switch($action){
	
	case 'getResourceGroup':

          	$response = $client->query(array(
				    'TableName' => $tableName,
				    'KeyConditions' => array(
				        'bookmark_resources_group_email' => array(
				            'ComparisonOperator' => 'EQ', 
				            'AttributeValueList' => array(
				                array('S' => $userEmail)
				            )
				        )
				    )    
				 ));  
		   		                    
           if(count($response)>0){ 
				 
				 			echo json_encode($response['Items']);
				   }
				   else{
				            echo json_encode(array('message'=>'error'));
				   }
  break;
  
  case 'editResourceGroup':
				
				
					
				 	$resourcegroupName = $result->Resourcegroup_name;
					$resourceDescription = $result->Resourcegroup_decription;
				
							
					$response = $client->updateItem ( array (
					    "TableName" => $tableName,
					   'Key' => array(
												        'bookmark_resources_group_email' => array(Type::STRING => $userEmail),
												        'bookmark_resources_group_range' => array(Type::STRING => $result->Resourcegroup_id)
												    ),
					    "ExpressionAttributeNames" => array (
					        "#NA" => "bookmark_resources_group_name",
					        "#A" => "bookmark_resources_group_description"
					    ),
					    "ExpressionAttributeValues" =>  array (
					        ":val1" => array("S" => $resourcegroupName),
					        ":val2" => array("S" => $resourceDescription)
					    ) ,
					    "UpdateExpression" => "set #NA = :val1, #A = :val2",
					    "ReturnValues" => "ALL_NEW" 
					) );			
							
							
				echo json_encode(array('message'=>'success'));
				
				exit;
  		
  break; 
  
  case 'addResourceGroup':
  					
  					 $response = $client->putItem(array(
                               "TableName" => $tableName,
                                     "Item" => $client->formatAttributes(array(
                                     "bookmark_resources_group_description" => $result->Resourcegroup_decription,
                                     "bookmark_resources_group_email" => $userEmail,
                                     "bookmark_resources_group_name" => $result->Resourcegroup_name,
                                     "bookmark_resources_group_range" => md5($userEmail.date('D-M-Y h:i:s')),
                                     "bookmark_user_updated_date" => date('D-M-Y h:i:s')
                                             )),
          "ReturnConsumedCapacity" => 'TOTAL'
          ));
          
          if(count(response)>0){
					echo json_encode(array('message'=>'success'));			
					}
		  else{
		  			echo json_encode(array('message'=>'error'));	
		  	}
		  			
  break;
  
  case 'removeReasourceGroup':
  
  	$response = $client->deleteItem(array(
     						   'TableName' => $tableName,
       								 'Key' => array(
         								   'bookmark_resources_group_email'   => array('S' => $userEmail),
           								   'bookmark_resources_group_range' => array('S' => $result->id)
        					)
    				));
    	if(count(response)>0){
					echo json_encode(array('message'=>'success'));			
					}
		  else{
		  			echo json_encode(array('message'=>'error'));	
		  	}		
   break;
   
  default;				   
				   
	}			 