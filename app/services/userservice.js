/*
This handles retrieving data and is used by userController
*/
bookmarkApp.service('userService',['$http','$resource','serviceHelper', function ($http,$resource,serviceHelper)  {
var singnup = serviceHelper.User;
	
	 var user = {};

	 	//Registration
	 	user.signUp = function (postData) {
	 		postData.actionType = "signup";
	 			return request= $http({
					method:"post",
					url:'lib/user.php',
					data:postData }

				});	
    	
	 	};
    	
    return user;

	        }]);