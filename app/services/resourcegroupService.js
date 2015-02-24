
bookmarkApp.service('resourcegroupService',['$http','$resource','serviceHelper', function ($http,$resource,serviceHelper) {
   var resourcegroup  = serviceHelper.Resourcegroup;
	var resource = {};

    // Registration
	 resource.addresourcegroup = function (postData) {
     
     		postData.actionType = "addResourceGroup";
			console.log(postData);
		     return request= $http({
					method:"post",
					url:'lib/resorceGroup.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	

    }
	 resource.getGroups = function (token) {
	 	postData={};
	 	
		postData.actionType = "getResourceGroup";
		
		     return request= $http({
					method:"post",
					url:'lib/resorceGroup.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
				 
		 
	    };
	    
   resource.editresourcegroup = function(postdata){
   	
   	    postdata.actionType = "editResourceGroup";
   	
	    return request= $http({
					method:"post",
					url:'lib/resorceGroup.php',
					data:postdata,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
   }
   
   resource.removeresourcegroup = function(id,token){
	  		postdata={};
	    	postdata.actionType = "removeReasourceGroup";
	    	postdata.id	=	id;
	    	return request= $http({
					method:"post",
					url:'lib/resorceGroup.php',
					data:postdata,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});
   }

    return resource;
}]);