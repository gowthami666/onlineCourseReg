app.controller('searchController', function ($scope, $rootScope, $routeParams, $location, $http, Data){
	$scope.selectedRows =[];
				$scope.searchTable = {
				paginationPageSizes: [5, 10, 15],
				paginationPageSize: 5,
				selectedItems:$scope.selectedRows,
				 enableRowSelection:true,
				 enableSelectAll:true,
				 enableRowHeaderSelection: false,
				 multiSelect: false,
				 modifierKeysToMultiSelect:false,
				 noUnselect:true,
				columnDefs: [
				  { displayName:'class Number',field: 'classId',width:"80" },
				  { displayName:'Course Number',field: 'course_id',width:"85" },
				  { displayName:'Course Name',field: 'course_name',width:"120"},
				  { displayName:'Section',field: 'section_name' ,width:"50"},
				  { displayName:'Majors',field: 'majors',width:"100" },
				  { displayName:'School Name',field: 'school_name',width:"200"},
				  { displayName:'Term',field: 'term_name',width:"45" },
				  { displayName:'Instructor',field: 'instructor_name',width:"150" },
				  { displayName:'Total Capacity',field: 'total_capacity',width:"85"},
				  { displayName:'Enrolled',field: 'used_capacity',width:"60"},
				  { displayName:'Wait List',field: 'total_waitlist' ,width:"60"},
				  { displayName:'Wait List Capacity',field: 'used_waitlist',width:"90"},
				  { displayName:'Status',field: 'status',width:"90"}
				],
				onRegisterApi: function( gridApi ) {
					$scope.searchTableAPI = gridApi;
				}
		  };
		  
	$scope.doSearch = function (search) {
		//console.log(classId);
        Data.post('simpleSearch', {
            search: search
        }).then(function (results) {
			console.log(results);
			var arr =[];
			arr.push(results);
			$scope.searchTable.data = arr;
        });
    };
	
	
	
	$scope.classSelect = function()
	{
		//console.log("drtt"+$scope.gridApi.selection.selectRow);
		//console.log($scope.searchTableAPI.selection.getSelectedRows()[0].classIds);
		if($scope.searchTableAPI.selection.getSelectedRows().length> 0)
		{
			 var clas_id = $scope.searchTableAPI.selection.getSelectedRows()[0].classId ;
			 console.log(clas_id);
			 $scope.searchTableAPI.selection.clearSelectedRows();
			 var str = 'registration/'+clas_id;
			 $location.path(str);
		}
		else{
			Data.toast("Please select a row");
		}
	};
	
	$scope.closeClass = function()
	{
			if($scope.searchTableAPI.selection.getSelectedRows().length> 0)
		{
			 var clas_id = $scope.searchTableAPI.selection.getSelectedRows()[0].classId ;
			 console.log(clas_id);
			 Data.post('closeClass', {
					clas_id: clas_id
				}).then(function (results) {
					Data.toast(results);
					if (results.status == "success") {
						$scope.searchTableAPI.selection.clearSelectedRows();
						$location.path('search');
					}
				});
		}
	}; 
	
	
});