/**
 * 
 */
bookmarkApp.service('resourceService',['$http','$resource','serviceHelper', function ($http,$resource,serviceHelper) {
	//var baseResource = serviceHelper.BaseResources;
	//var note = serviceHelper.Activity;
	//var Resourcegroup = serviceHelper.Resourcegroup;
			
	var resource = {};

	resource.resourcegroupname = function (token) {
		postData={};
		
		postData.actionType = 'getResourceGroup'
		return request= $http({
					method:"post",
					url:'lib/resorceGroup.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
		
    };

    resource.addresource = function (postData) {
     
		postData.actionType = "addResources";
		
		     return request= $http({
					method:"post",
					url:'lib/resources.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});
       
    }
    
    resource.getresource = function (token) {
    	postData={};
	 	
		postData.actionType = "getResources";
		
		     return request= $http({
					method:"post",
					url:'lib/resources.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
	    };
	    resource.editresource = function(postdata){
	 	  
	 	postdata.actionType = "editResources";
   	
	    return request= $http({
					method:"post",
					url:'lib/resources.php',
					data:postdata,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
	 	  
	    }
	    resource.createNote = function (postData) {
	       
	    	postData.actionType = "createNotes";
	    	return request= $http({
					method:"post",
					url:'lib/notes.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});
	    }
	    
	    resource.removeresource = function(id,token){
	    	postdata={};
	    	postdata.actionType = "removeResources";
	    	postdata.id	=	id;
	    	return request= $http({
					method:"post",
					url:'lib/resources.php',
					data:postdata,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});
	    }
	    resource.getactivity = function (token,resourceName) {
	    	postData.actionType = "getnotes";
		    postData.resourceName = resourceName;
		     return request= $http({
					method:"post",
					url:'lib/notes.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
		    }; 
    return resource;
}]);