var ThankApp = angular.module("thankApp", ["ngRoute"]);

function ENV(str) {
	return ( DEV ? "/app_dev.php" : "" ) + str;
}

var apiUrl = ENV("/api");

var VIEWS = {
	INDEX: 1,
	FOLLOWS: 2,
	FOLLOWERS: 3
}

ThankApp.config(function($routeProvider) {
    $routeProvider
	    .when("/", {
	    	templateUrl : "/templates/index.html",
	    	controller  : "IndexViewController"
	    })
	    .when("/follows", {
	    	templateUrl : "/templates/follows.html",
	    	controller  : "FollowsViewController"
	    })
	    .when("/followers", {
	    	templateUrl : "/templates/followers.html",
	    	controller  : "FollowersViewController"
	    })
	    .otherwise({
	        redirectTo: '/'
	    });
});

ThankApp.controller("MainController", ["$scope", "$http", function($scope, $http) {

    $scope.currentUserData = {};

    $scope.logout = function() {
    	location.href = ENV("/logout");
    };

    $http
        .post(apiUrl, { action: "currentUserData" })
        .success(function(data) {
        	if (!data.error) $scope.currentUserData = data;
        	console.log(data, $scope.currentUserData);
        });
}]);

ThankApp.controller("IndexViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {
    
    $scope.$parent.currentView = VIEWS.INDEX;
    
}]);

ThankApp.controller("FollowsViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {
    
    $scope.$parent.currentView = VIEWS.FOLLOWS;
    
}]);

ThankApp.controller("FollowersViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {
    
    $scope.$parent.currentView = VIEWS.FOLLOWERS;
    
}]);