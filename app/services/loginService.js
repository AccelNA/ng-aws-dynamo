
// This handles retrieving data and is used by controllers.

bookmarkApp.service('loginService', [ '$http', '$resource', 'serviceHelper',
		function($http, $resource, serviceHelper) {

			var users = {};
			var $json = {};
			var user = serviceHelper.User;

			/*
			 * Authentication operation
			 */

			users.signIn = function(userName, password) {

				var postData = {

					userEmail : userName,
					userPassword : password,
					actionType:'login'
				};
				
				return request= $http({
					method:"post",
					url:'lib/user.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }

				});	
				
		}

			users.logout = function(token) {
					
				var postData = {
					actionType:'logout'
				};	
						
				return request= $http({
					method:"post",
					url:'lib/user.php',
					data:postData,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				});	
						
			}

			return users;
		} ]);