app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
	
    $scope.login = {};
    $scope.signup = {};
	$scope.selecT = {};
	//$scope.classLevels =[{name:'Graduate', value:'1'},{name:'Under Graduate', value:'2'}];
	//$scope.majors=[{name:'Computer Science', value:'1'},{name:'Computer Engineering', value:'2'}];
	//$scope.terms=[];
	//$scope.schools=[{name:'Erik Johnsson', value:'1'},{name:'Jsom', value:'2'}];
	/*  angular.element(document).ready(function () {
        var pass = document.getElementById('password');
		var meter = document.getElementById('password-strength-meter');
		pass.addEventListener('input', function()
		{
			var val = pass.value;
			var result = zxcvbn(val);
			// Update the password strength meter
			meter.value = result.score;
		});

    }); */

	if($rootScope.terms == undefined)
	{
		Data.get('terms').then(function (results)
		{
			$rootScope.terms = results.terms;
		});
	}
	if($rootScope.majors == undefined)
	{
		Data.get('majors').then(function (results)
		{
			$rootScope.majors = results.majors;
		});
	}
	if($rootScope.instructors == undefined)
	{
		Data.get('instructors').then(function (results)
		{
			$rootScope.instructors = results.instructors;
		});
	}
	if($rootScope.classLevels == undefined)
	{
		Data.get('classLevels').then(function (results)
		{
			$rootScope.classLevels = results.classLevels;
		});
	}
	if($rootScope.schools == undefined)
	{
		Data.get('schools').then(function (results)
		{
			$rootScope.schools = results.schools;
		});
	}
	if($rootScope.courses == undefined)
	{
		Data.get('courses').then(function (results)
		{
			$rootScope.courses = results.courses;
		});
	}
	if($rootScope.sections == undefined)
	{
		Data.get('sections').then(function (results)
		{
			$rootScope.sections = results.sections;
		});
	}

	
	//console.log($rootScope.terms);
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
           // if (results.status == "success") {
                $location.path('search');
            //}
        });
    };
	$scope.passvalid = function (customer) {
		$scope.value = $scope.signup.password;
		if($scope.value != null)
		{
			$scope.result = zxcvbn($scope.value);
			$scope.meter_value = $scope.result.score;
		}
	};
	
    $scope.signup = {fname:'',lname:'',email:'',password:'',classLevel:'',major:'',term:'',school:''};
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('search');
            }
        });
    };
	$scope.selecT = {school:'',term:'',classLevel:'',course:'',major:'',section:'',capacity:'',instructor:'',waitlist:''};
	$scope.Add = function (customer) {
		Data.post('Add', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('search');
            }
			else{
				$location.path('select');
			}
        });
    };
		
		
		
		
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
});