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

$tableName = 'bookmark_resources_notes';
$dynamodb = DynamoDbClient::factory(array('region' => $region));
$instanceId = file_get_contents("http://169.254.169.254/latest/meta-data/instance-id");
$client = DynamoDbClient::factory($config);

$action = $result->actionType;

$userEmail = trim($_COOKIE['userEmail']);
$userEmail = trim($userEmail,'"');

switch($action){
	
	case 'getnotes':

			$reourceName = $result->resourceName;
			
          	$response = $client->query(array(
				    'TableName' => $tableName,
				    'KeyConditions' => array(
				        'resources_name' => array(
				            'ComparisonOperator' => 'EQ', 
				            'AttributeValueList' => array(
				                array('S' => $reourceName)
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
  

  case 'createNotes':
  			
  			
  					
  		$response = $client->putItem(array(
                               "TableName" => $tableName,
                                     "Item" => $client->formatAttributes(array(
                                     'resources_notes'=>$result->Activity_notes,
                                     'resources_id'=>sha1(md5($userEmail.date('D-M-Y h:i:s'))),
                                     'resources_name'=>$result->Activity_resource_name
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