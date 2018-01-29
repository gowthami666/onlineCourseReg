app.controller('selectController', function($scope, $rootScope, $routeParams, $location, $http, Data){

if($rootScope.courses == undefined)
	{
		Data.get('schools').then(function (results)
		{
			$rootScope.courses = results.courses;
		});
	}

	
	/*  $scope.schools = null;
     $scope.fillSchools = function () {
                $http({
                    method: 'POST',
                    url: 'api/v1/select.php?school=true',
                    data: {}
                }).success(function (data) {
                    $scope.schools = data;
                });
            };
            
     $scope.fillSchools(); */
	
});