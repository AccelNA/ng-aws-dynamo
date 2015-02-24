/**
 * 
 */
/*
This controller retrieves data from the resource group service
*/
bookmarkApp.controller('resourceGroupController',['$scope','$routeParams','resourcegroupService','$cookieStore','$location','ngTableParams','$filter', function ($scope, $routeParams, resourcegroupService,$cookieStore,$location,ngTableParams,$filter) {

	
	$scope.userData = {};
	var selectedname = $routeParams.name;
	var descriptionData = $routeParams.description;
	var id = $routeParams.id;

    // Add Resource Group
    $scope.fnAddresourcegroup = function (isValid) {
        if (isValid) {
        	$scope.userData.Resourcegroup_user_token =$cookieStore.get("token");
        	result= resourcegroupService.addresourcegroup($scope.userData);
        	
        	result.success(function(data){
										 suceesResult = data.message;
										
										 switch(suceesResult){
											case 'error':
												 $scope.errormsg = "The Resource Group is not added due to some network issues";
											break;
											case 'success':	
											    $scope.successmsg = "Successfully added the resource group";
												$scope.grouplist();
											break;
											default:	
										}	
			});    
        	    
        	}   
        };
	    //Edit group
	    $scope.fnEditgroup = function (groups) {
	    	$scope.groupdata = groups;
	    	console.log(groups);
	    	description = groups.bookmark_resources_group_description.S;
	    	$scope.Resourcegroup_id=groups.bookmark_resources_group_range.S;
	    	$scope.Resourcegroup_name = groups.bookmark_resources_group_name.S;
	    	$scope.Resourcegroup_decription = description?description: null;
	    	$location.path('/resourcegroupedit/'+$scope.Resourcegroup_id+"/"+$scope.Resourcegroup_name+"/"+$scope.Resourcegroup_decription);
   
	    };



//
    //Delete Group
    $scope.fnRemoveResourceGroup = function (id) {
    	//alert(id);
    	$scope.id =id;
    	$scope.token = $cookieStore.get("token");
        result = resourcegroupService.removeresourcegroup($scope.id,$scope.token).then(function(success){
        	//$scope.grouplist();
        //	$location.path('/resourcegroup');
        	$scope.successmsg = "Successfully Removed the resource group";
           	$scope.resourcegroupForm.$setPristine();
            $scope.grouplist();
        	  },function(error) {
        	    	$scope.showWait = false;
        	        $scope.errormsg = "The Resource Group is not removed due to some network issues";
        	});    
        	} ;   



// update group
	    $scope.initUpdate = function () {
	    	$scope.nm = selectedname;
	    	$scope.id = id;
	    	if("null" !== descriptionData) {
	    		$scope.desc = descriptionData;
	    	}else {
	    		$scope.desc = "";
	    	}
	    };
	    
	    $scope.updateVal = function () {
	    	$scope.postdata = {};
	    	$scope.postdata.Resourcegroup_id = id;
	    	$scope.postdata.Resourcegroup_name = $scope.nm;
	    	$scope.postdata.Resourcegroup_decription = $scope.desc
	    	$scope.token = $cookieStore.get("token");
	    	$scope.postdata.Resourcegroup_user_token = $scope.token;
	    	resourcegroupService.editresourcegroup($scope.postdata).then(function(success){
        	$location.path('/resourcegroup');
        	$scope.successmsg = "Successfully updated the resource group";
           }); 
	   };
	    
/*
 * This function fetch entire Resource groups
 */  
 
        $scope.grouplist = function () {
        	$scope.token = $cookieStore.get("token");
            resourcegroupService.getGroups($scope.token).then(function (respData){
            	
            $scope.tabledata = respData.data;
            
	           $scope.tableParams = new ngTableParams({
	                page: 1,              // show first page
	                count: 500,
	                sorting: {name: 'asc'}// initial sorting
	            },
	            {
	                total: $scope.tabledata.length, // length of data
	                getData: function ($defer, params) {
	                   // var orderedData = params.sorting() ? $filter('orderBy')($scope.tabledata, params.orderBy()) : $scope.tabledata;
	                    var orderedData = params.filter() ? $filter('filter')(orderedData, params.filter()) : $scope.tabledata;
	                   // $scope.groups = orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count());
	
	                    params.total(orderedData.length); // set total for recalc pagination
	                   // $defer.resolve($scope.groups);
	               
	                }
	            });
       
            });
        };
}]);