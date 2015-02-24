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

$tableName = 'bookmark_resources';
$dynamodb = DynamoDbClient::factory(array('region' => $region));
$instanceId = file_get_contents("http://169.254.169.254/latest/meta-data/instance-id");
$client = DynamoDbClient::factory($config);

$action = $result->actionType;

$userEmail = trim($_COOKIE['userEmail']);
$userEmail = trim($userEmail,'"');

switch($action){
	
	case 'getResources':

          	$response = $client->query(array(
				    'TableName' => $tableName,
				    'KeyConditions' => array(
				        'resources_email' => array(
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
  
  case 'editResources':
				
				
					
				 	$resourceGroupName   = $result->Resource_resourcegroup_name->bookmark_resources_group_name->S;
							
					$response = $client->updateItem ( array (
					    "TableName" => $tableName,
					     'Key' => array(
												        'resources_email' => array(Type::STRING => $userEmail),
												        'resources_id' => array(Type::STRING => $result->Resource_id)
												    ),
					    "ExpressionAttributeNames" => array (
					    	"#LKMNA"=>"resources_name",
					    	"#KMNA"=>"resources_path",
					    	"#MNA"=>"resources_priority",
					        "#NA" => "resources_group_name",
					        "#A" => "resources_description"
					    ),
					    "ExpressionAttributeValues" =>  array (
					        ":val1" => array("S" => $result->Resource_name),
					        ":val2" => array("S" => $result->Resource_path),
					        ":val3" => array("S" => $result->Resource_priority),
					        ":val4" => array("S" => $resourceGroupName),
					        ":val5" => array("S" => $result->Resource_decription)
					    ) ,
					    "UpdateExpression" => "set #LKMNA = :val1, #KMNA = :val2, #MNA = :val3, #NA = :val4, #A = :val5",
					    "ReturnValues" => "ALL_NEW" 
					) );	
							
				echo json_encode(array('message'=>'success'));
				
				exit;
  		
  break; 
  
  case 'addResources':
  			
  			
  					
  		$response = $client->putItem(array(
                               "TableName" => $tableName,
                                     "Item" => $client->formatAttributes(array(
                                     'resources_email'=>$userEmail,
                                     'resources_id'=>sha1(md5($userEmail.date('D-M-Y h:i:s'))),
                                     'resources_description'=>$result->Resource_decription,
                                     'resources_group_name'=>$result->Resource_resourcegroup_name->bookmark_resources_group_name->S,
                                     'resources_name'=>$result->Resource_name,
                                     'resources_path'=>$result->Resource_path,
                                     'resources_priority'=>$result->Resource_priority,
                                     'resources_updated_date'=>date('D-M-Y h:i:s'),
                                     "bookmark_resources_group_range" => md5($userEmail.date('D-M-Y h:i:s')),
                                     "resources_created_date" => date('D-M-Y h:i:s')
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
  
  case 'removeResources':
  				
  				
  				
  					$response = $client->deleteItem(array(
     						   'TableName' => $tableName,
       								 'Key' => array(
         								   'resources_email'   => array('S' => $userEmail),
           								   'resources_id' => array('S' => $result->id)
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