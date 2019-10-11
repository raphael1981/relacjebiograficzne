app.controller('routeoutController',['$scope','$http', function($scope,$http) {	

	
	 $scope.goToTrans = function(address){
		 var promise =  $http({url : address,				
							method : "GET",				
							headers : {'Content-Type':'application/json; charset=utf-8','Auth': '123'}})
                          .success( function(response, status, headers, config) {
                                              $scope.data = response;
                                   }).error(function(errResp) {
                                  console.log("error fetching url");
                               });
							   
		promise.then(function(data){
             console.log(data.data);
            });					   
	}



}]);