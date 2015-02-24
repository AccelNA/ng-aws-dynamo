/*
This controller retrieves data from the userService and associates it with the $scope
 */
 
var suceesResult;
 
bookmarkApp
		.controller(
				'userController',
				[
						'$scope',
						'userService',
						'loginService',
						'$cookieStore',
						'$location',
						function($scope, userService, loginService,
								$cookieStore, $location) {

							$scope.signupData = {};
							$scope.changePassData = {};
							$scope.userData = {}
							$scope.Logindata = {};

							init();

							function init() {
								
								$scope.Logindata.bookmark_user_email = "";
								$scope.Logindata.bookmark_user_password = "";
							}

							// Registration
							$scope.fnSignup = function(isValid) {
								if (isValid) {
									result = userService.signUp($scope.signupData)
									
									result.success(function(data){
										 suceesResult = data.message;
										 switch(suceesResult){
											case 'success':
												$scope.successmsg = "You have Succesfully Registered.";
											break;
											case 'error':
												$scope.successmsg = "Something wrong! Please try again";
											break;
											default:
												
											break;
										}	
									});	 
												
								}
							};

							$scope.loginuser = function(isValid) {

								// Two parameters passing as Authdetails ;
								if (isValid) {

								result = loginService.signIn($scope.Logindata.bookmark_user_email,$scope.Logindata.bookmark_user_password);
											
										result.success(function(data){
										 suceesResult = data.message;
										
										 switch(suceesResult){
											case 'error':
												$scope.errormsg = "Invalid Username or password";
											break;
											case 'success':	
											    $cookieStore.put('token',data.token);
											    $cookieStore.put('userEmail',data.userEmail);
												$location.path('/resourcehome');
											break;
											default:	
										}	
										});
										
										
									};
							};

							$scope.logout = function() {
									$cookieStore.remove('token');
							}
	}]);