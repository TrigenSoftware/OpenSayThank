var ThankApp = angular.module("thankApp", ["ngRoute"]);

var apiUrl = "/api",

	VIEWS = {
		INDEX: 1,
		PROFILE: 5,
		FOLLOWS: 2,
		FOLLOWERS: 3,
		SEARCH: 4,
		INVITE: 6
	},

	INDEX_VIEWS = {
		FEED: 1,
		SAID: 2,
		GOT: 3
	},

	MODAL_STATE = {
		HIDDEN: 0,
		THANK: 1,
		MESSAGE: 2
	},

	VK_ID = 4545689;

function AUTH(data) {
	if (data.error && data.error == "access denied")
		console.log("api access denied");
		//location.href = "/login";
}

var observersStorage = [];

function killObservers(observerGroup) {
	observersStorage.forEach(function(observer){
		if (!observerGroup || observer.group == observerGroup) 
			clearInterval(observer.id);
	});
}

function runObserver(observerGroup, observer) {
	observer();
	observersStorage.push({
		group: observerGroup,
		id: setInterval(observer, 2000)
	});
}


Number.prototype.toDualDigit = function() {
	var str = this + "";
	return str.length === 1 ? "0" + str : str;
};


VK.init({
    apiId: VK_ID
});

ThankApp.config(function($routeProvider, $locationProvider) {
	$locationProvider.html5Mode(true);
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
	    .when("/user/:username/follows", {
	    	templateUrl : "/templates/follows.html",
	    	controller  : "FollowsViewController"
	    })
	    .when("/user/:username/followers", {
	    	templateUrl : "/templates/followers.html",
	    	controller  : "FollowersViewController"
	    })
	    .when("/search", {
	    	templateUrl : "/templates/search.html",
	    	controller  : "SearchViewController"
	    })
	    .when("/user/:username", {
	    	templateUrl : "/templates/profile.html",
	    	controller  : "ProfileViewController"
	    })
	    .when("/search/:query", {
	    	templateUrl : "/templates/search.html",
	    	controller  : "SearchViewController"
	    })
	    .when("/invite", {
	    	templateUrl : "/templates/invite.html",
	    	controller  : "InviteViewController"
	    })
	    .otherwise({
	        redirectTo: '/'
	    });
});


ThankApp.directive("gtImageUpload", function() {
    return {
        restrict: "A",
        scope: {
            "image": "=gtImageUpload"
        },
        link: function ($scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader(),
                	image  = changeEvent.target.files[0];

                reader.onload = function (loadEvent) {
                    $scope.$apply(function () {
                        $scope.image = loadEvent.target.result;
                    });
                };

                if(image.type.indexOf("image") == 0) reader.readAsDataURL(image);
            });
        }
    };
});


ThankApp.directive("gtLoadNext", function() {
    return {
        restrict: "A",
        scope: {
            "gtLoadNext": "&"
        },
        link: function ($scope, element, attributes) {
        	var target = document.body;
        	angular.element(window).scroll(function (e) {
            	if ((target.scrollHeight - target.offsetHeight - target.scrollTop - window.innerHeight) < 100)
	                $scope.$apply(function() {
	                    $scope.gtLoadNext();
	                });
            });
        }
    };
});


ThankApp.controller("MainController", ["$scope", "$http", "$location", "$sce", function($scope, $http, $location, $sce) {

	$scope.modalState = MODAL_STATE.HIDDEN;
	$scope.modal = {
		title: "",
		body: "",
		thankto: "",
		attachment: ""
	};

    $scope.currentUserData = {};
    $scope.searchQuery = '';


    $scope.trustAsHtml = $sce.trustAsHtml;

    $scope.humanTiming = function(time) {
    	time = new Date(time);
    	var diff = new Date() - time;
    	
    	if (diff < 60000) return "меньше минуты назад";
    	if (diff < 3600000) {
    		var min = Math.floor(diff / 60000), lst = min + "";
    		lst = lst[lst.length - 1]*1;

    		if ( min === 1 )
    			return "минуту назад";
    		else
    		if ( lst == 1 && min > 20 )
    			return min + " минуту назад";
    		else
    		if( (lst == 2 || lst == 3 || lst == 4) && (min > 20 || min < 10))
    			return min + " минуты назад";
    		else 
    			return min + " минут назад";
    	}
    	if (diff < 86400000) {
    		var hour = Math.floor(diff / 3600000), lst = hour + "";
    		lst = lst[lst.length - 1]*1;

    		if ( hour === 1 )
    			return "час назад";
    		else
    		if ( lst == 1 && hour > 20 )
    			return hour + " час назад";
    		else
    		if( (lst == 2 || lst == 3 || lst == 4) && (hour > 20 || hour < 10))
    			return hour + " часа назад";
    		else 
    			return hour + " часов назад";
     	}

    	return time.toLocaleDateString("ru-RU", {
    		year: "numeric", 
    		month: "long", 
    		day: "numeric"
    	}) + " " + time.getHours().toDualDigit() + ":" + time.getMinutes().toDualDigit();
    };


	$scope.search = function(){
    	$location.path("/search/" + $scope.searchQuery);
    };

    $scope.logout = function() {
    	localStorage.clear();
    	location.href = "/logout";
    };

    $scope.invite = function() {
    	$location.path("/invite");
    };

    $scope.inviteVkFriend = function(friendData) {
    	VK.Api.call('wall.post', { owner_id: friendData.id, message: "Приглашаю тебя на сайт saythank.me"}, function(){});
    };

    $scope.showSayThankModal = function(userData) {
    	$scope.modalState = MODAL_STATE.THANK;
    	$scope.modal = {
			title: "Сказать Спасибо",
			body: "Текст сообщения",
			thankto: userData && userData.username || "",
			userData: userData,
			attachment: ""
		};
    };

    $scope.hideModal = function() {
    	$scope.modalState = MODAL_STATE.HIDDEN;
    	$scope.modal = {
			title: "",
			body: "",
			thankto: "",
			attachment: ""
		};
		$scope.removeAttachment();
    };

    $scope.modalBodyLimit = function() {
    	return 100 - document.sayThankForm.body.value.length;
    };

    $scope.removeAttachment = function() {
    	$scope.modal.attachment = "";
    	angular.element("[gt-image-upload]").val("");
    };

    $scope.sayThank = function() {
    	if ($scope.modal.userData) $scope.modal.userData.gotThanks++;

    	$http
    		.post(apiUrl, { 
    			action: "sayThank", 
    			thankto: $scope.modal.thankto,
    			body: $scope.modal.body,
    			attachment: $scope.modal.attachment
    		})
	        .success(function(data) {
	        	AUTH(data);
	        	
	        	if (data.error) return $scope.modal.userData.gotThanks--;
	        	$scope.hideModal();
	        });
    };

    $scope.removeThank = function(thankData) {
    	thankData.removed = true;

    	$http
    		.post(apiUrl, { 
    			action: "removeThank", 
    			id: thankData.id
    		})
	        .success(function(data) {
	        	AUTH(data);
	        	
	        	if (data.error) return thankData.removed = false;
	        });
    };


    $scope.giveFive = function(thankData) {
    	thankData.fived = true;
	    thankData.gotFives += 5;

		$http
	        .post(apiUrl, { action: "giveFive", id: thankData.id })
	        .success(function(data) {
	        	AUTH(data);

	        	if (!data.error) return;

	        	thankData.fived = false;
	        	thankData.gotFives -= 5;
	        });
    };

    $scope.removeFive = function(thankData) {
    	thankData.fived = false;
	    thankData.gotFives -= 5;

		$http
	        .post(apiUrl, { action: "removeFive", id: thankData.id })
	        .success(function(data) {
	        	AUTH(data);

	        	if (!data.error) return;

	        	thankData.fived = true;
	        	thankData.gotFives += 5;
	        });
    };


    $scope.follow = function(userData) {
    	if (!userData || !userData.username) return;

	    userData.follow = true;
    	userData.followers++;

    	$http
    		.post(apiUrl, { action: "follow", username: userData.username })
	        .success(function(data) {
	        	AUTH(data);

	        	if (!data.error) return;

	        	userData.follow = false;
	        	userData.followers--;
	        });
    }

    $scope.unfollow = function(userData) {
    	if (!userData || !userData.username) return;

	    userData.follow = false;
    	userData.followers--;

    	$http
    		.post(apiUrl, { action: "unfollow", username: userData.username })
	        .success(function(data) {
	        	AUTH(data);

	        	if (!data.error) return;

	        	userData.follow = true;
	        	userData.followers++;
	        });
    }

    $http
        .post(apiUrl, { action: "currentUserData" })
        .success(function(data) {
        	AUTH(data);

        	if (data.error) return;

        	$scope.currentUserData = data;
        	if (data.username) localStorage.currentUsername = data.username;
        	if (data.vkontakteId) localStorage.currentVkontakteId = data.vkontakteId;
        });

}]);

ThankApp.controller("IndexViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {

	killObservers();
    
    $scope.$parent.currentView = VIEWS.INDEX;
    $scope.$parent.searchQuery = "";

    $scope.currentView = null;
    $scope.thanks = [];
    $scope.fromOffset = null;

    runObserver("currentUser", function(){
    	$http
	        .post(apiUrl, { action: "currentUserData" })
	        .success(function(data) {
	        	AUTH(data);

	        	if (data.error) return;

	        	$scope.currentUserData = data;
	        	if (data.username) localStorage.currentUsername = data.username;
        		if (data.vkontakteId) localStorage.currentVkontakteId = data.vkontakteId;
	        });
	});

    $scope.setIndexView = function(index) {
    	if (index == $scope.currentView) return;

    	killObservers("thank");

    	$scope.thanks = [];
    	$scope.fromOffset = null;
    	$scope.currentView = index;
    	var action;

    	switch (index) {
    		case INDEX_VIEWS.FEED:
				action = "thanksWithData";
				break;

			case INDEX_VIEWS.SAID:
				action = "saidThanksWithData";
				break;

			case INDEX_VIEWS.GOT:
    			action = "gotThanksWithData";
    	}
    	
		runObserver("thank", function(){
			$http
			    .post(apiUrl, { 
			    	action: action, 
			    	username: $scope.$parent.currentUserData.username || localStorage.currentUsername,
			    	from: !$scope.thanks[0] ? $scope.fromOffset : $scope.thanks[0].id 
			    })
			    .success(function(data) {
			    	AUTH(data);

			    	if (data.error) return;
			    	
			    	$scope.thanks = data.concat($scope.thanks);
			    });
		});

		$scope.$parent.loadNext = function(){
			if (!$scope.thanks[$scope.thanks.length - 1] || $scope.fromOffset == $scope.thanks[$scope.thanks.length - 1].id) return;
			
			$scope.fromOffset = $scope.thanks[$scope.thanks.length - 1].id;
			$http
			    .post(apiUrl, { 
			    	action: action, 
			    	username: $scope.$parent.currentUserData.username || localStorage.currentUsername,
			    	from: -$scope.fromOffset 
			    })
			    .success(function(data) {
			    	AUTH(data);

			    	if (data.error) return;
			    	
			    	$scope.thanks = $scope.thanks.concat(data);
			    });
		};
    };
    
    $scope.setIndexView(INDEX_VIEWS.FEED);
}]);

ThankApp.controller("ProfileViewController", [ "$scope", "$http", "$location", "$routeParams", function($scope, $http, $location, $params) {

	killObservers();
    
    if ($params.username == $scope.$parent.currentUserData.username) 
    	return $location.path("/");

    $scope.$parent.currentView = VIEWS.PROFILE;
    $scope.$parent.searchQuery = "";
    $scope.userData = {};

    $scope.currentView = null;
    $scope.thanks = [];
    $scope.fromOffset = null;

    $scope.setIndexView = function(index) {
    	if (index == $scope.currentView) return;

    	killObservers("thank");

    	$scope.thanks = [];
    	$scope.fromOffset = null;
    	$scope.currentView = index;
    	var action;

    	switch (index) {
    		case INDEX_VIEWS.FEED:
				action = "thanksWithData";
				break;

			case INDEX_VIEWS.SAID:
				action = "saidThanksWithData";
				break;

			case INDEX_VIEWS.GOT:
    			action = "gotThanksWithData";
    	}

		runObserver("thank", function(){
			$http
			    .post(apiUrl, { 
			    	action: action, 
			    	username: $scope.userData.username,
			    	from: !$scope.thanks[0] ? $scope.fromOffset : $scope.thanks[0].id
			    })
			    .success(function(data) {
			    	AUTH(data);

			    	if (data.error) return;
			    	
			    	$scope.thanks = data.concat($scope.thanks);
			    });
		});

		$scope.$parent.loadNext = function(){
			if (!$scope.thanks[$scope.thanks.length - 1] || $scope.fromOffset == $scope.thanks[$scope.thanks.length - 1].id) return;
			
			$scope.fromOffset = $scope.thanks[$scope.thanks.length - 1].id;
			$http
			    .post(apiUrl, { 
			    	action: action, 
			    	username: $scope.userData.username,
			    	from: -$scope.fromOffset 
			    })
			    .success(function(data) {
			    	AUTH(data);

			    	if (data.error) return;
			    	
			    	$scope.thanks = $scope.thanks.concat(data);
			    });
		};
    };

    runObserver("user", function(){
    	$http
	        .post(apiUrl, { action: "userData", username: $params.username })
	        .success(function(data) {
	        	AUTH(data);

	        	if (data.error) return;

	        	$scope.userData = data;
	        	if (!$scope.thanks.length) $scope.setIndexView(INDEX_VIEWS.FEED);
	        });
	});

}]);

ThankApp.controller("FollowsViewController", [ "$scope", "$http", "$location", "$routeParams", function($scope, $http, $location, $params) {

	killObservers();
    
    if ($params.username == $scope.$parent.currentUserData.username) 
    	$location.path("/follows");

    if (!$params.username) 
    	$scope.$parent.currentView = VIEWS.FOLLOWS;

    $scope.$parent.searchQuery = "";
    $scope.follows = [];
    $scope.fromOffset = null;

    runObserver("follows", function(){
		$http
		    .post(apiUrl, { 
		    	action: "follows", 
        		username: $params.username || $scope.$parent.currentUserData.username || localStorage.currentUsername, 
		    	from: !$scope.follows[0] ? $scope.fromOffset : $scope.follows[0].id 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.follows = data.concat($scope.follows);
		    });
	});

	$scope.$parent.loadNext = function(){
		if (!$scope.follows[$scope.follows.length - 1] || $scope.fromOffset == $scope.follows[$scope.follows.length - 1].id) return;
		
		$scope.fromOffset = $scope.follows[$scope.follows.length - 1].id;
		$http
		    .post(apiUrl, { 
		    	action: "follows", 
		    	username: $params.username || $scope.$parent.currentUserData.username || localStorage.currentUsername,
		    	from: -$scope.fromOffset 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.follows = $scope.follows.concat(data);
		    });
	};
    
}]);

ThankApp.controller("FollowersViewController", [ "$scope", "$http", "$location", "$routeParams", function($scope, $http, $location, $params) {

	killObservers();
    
    if ($params.username == $scope.$parent.currentUserData.username) 
    	$location.path("/followers");

    if (!$params.username) 
    	$scope.$parent.currentView = VIEWS.FOLLOWERS;

    $scope.$parent.searchQuery = "";
    $scope.followers = [];
    $scope.fromOffset = null;
    
    runObserver("followers", function(){
		$http
		    .post(apiUrl, { 
		    	action: "followers", 
        		username: $params.username || $scope.$parent.currentUserData.username || localStorage.currentUsername, 
		    	from: !$scope.followers[0] ? $scope.fromOffset : $scope.followers[0].id 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.followers = data.concat($scope.followers);
		    });
	});

	$scope.$parent.loadNext = function(){
		if (!$scope.followers[$scope.followers.length - 1] || $scope.fromOffset == $scope.followers[$scope.followers.length - 1].id) return;
		
		$scope.fromOffset = $scope.followers[$scope.followers.length - 1].id;
		$http
		    .post(apiUrl, { 
		    	action: "followers", 
		    	username: $params.username || $scope.$parent.currentUserData.username || localStorage.currentUsername,
		    	from: -$scope.fromOffset 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.followers = $scope.followers.concat(data);
		    });
	};

}]);

ThankApp.controller("SearchViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {

	killObservers();
    
    $scope.$parent.currentView = VIEWS.SEARCH;
    $scope.searchResult = [];
    $scope.fromOffset = null;

    $scope.$parent.searchQuery = $params.query || "";

    runObserver("search", function(){
		$http
		    .post(apiUrl, { 
		    	action: "searchUserWithData", 
        		query: $scope.$parent.searchQuery,
		    	from: !$scope.searchResult[0] ? $scope.fromOffset : $scope.searchResult[0].id 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.searchResult = data.concat($scope.searchResult);
		    });
	});

	$scope.$parent.loadNext = function(){
		if (!$scope.searchResult[$scope.searchResult.length - 1] || $scope.fromOffset == $scope.searchResult[$scope.searchResult.length - 1].id) return;
		
		$scope.fromOffset = $scope.searchResult[$scope.searchResult.length - 1].id;
		$http
		    .post(apiUrl, { 
		    	action: "searchUserWithData", 
		    	query: $scope.$parent.searchQuery,
		    	from: -$scope.fromOffset 
		    })
		    .success(function(data) {
		    	AUTH(data);

		    	if (data.error) return;
		    	
		    	$scope.searchResult = $scope.searchResult.concat(data);
		    });
	};
}]);

ThankApp.controller("InviteViewController", [ "$scope", "$http", "$routeParams", function($scope, $http, $params) {

	killObservers();
    
    $scope.$parent.currentView = VIEWS.INVITE;
    $scope.friends = [];
    $scope.fromOffset = 0;
    
    VK.Api.call('friends.get', {
		user_id: $scope.$parent.currentUserData.vkontakteId || localStorage.currentVkontakteId || "", 
		order: "hints", 
		count: 15, 
		fields: "photo_100", 
		name_case: "nom", 
		v: "5.25"
	}, function(r) { 
		if (!r.response) return;
		$scope.$apply(function(){
			$scope.friends = r.response.items;
		});
	}); 

	$scope.$parent.loadNext = function() {
		if ($scope.fromOffset >= $scope.friends.length) return;

		VK.Api.call('friends.get', {
			user_id: $scope.$parent.currentUserData.vkontakteId || localStorage.currentVkontakteId || "", 
			order: "hints", 
			count: 15, 
			fields: "photo_100", 
			name_case: "nom", 
			v: "5.25", 
			offset: $scope.fromOffset += 15
		}, function(r) { 
			if (!r.response) return;
			$scope.$apply(function(){
				$scope.friends = $scope.friends.concat(r.response.items);
			});
		}); 
	};
}]);