/******************************
 * 
 * CETEN services header CSS
 * 
 * @author Jonathan ARNAULT
 * @copyright Cercle des Élèves de TELECOM Nancy <bde@telecomnancy.net>
 * @license MIT
 * 
 *****************************/

var cetenControllers = angular.module('cetenControllers', []);

cetenControllers.controller('IndexCtrl', ['$scope', '$http', '$routeParams', '$rootScope', 'ngDialog',
  function ($scope, $http, $routeParams, $rootScope, ngDialog) {
    $rootScope.title = 'Accueil';
  
    $http.get(ceten.uri('/news.json')).success(function(data) {
        $scope.news = data;
    });
}]);

cetenControllers.controller('ClubListCtrl', ['$scope', '$http', '$routeParams', '$rootScope', 'ngDialog',
  function ($scope, $http, $routeParams, $rootScope, ngDialog) {
    $rootScope.title = 'Les clubs';

    $http.get(ceten.uri('/clubs.json')).success(function(data) {
        $scope.clubs = data;
    });
}]);


cetenControllers.controller('PartnerListCtrl', ['$scope', '$http', '$routeParams', '$rootScope', 'ngDialog',
  function ($scope, $http, $routeParams, $rootScope, ngDialog) {
    $rootScope.title = 'Nos partenaires';

    $http.get(ceten.uri('/partenaires.json')).success(function(data) {
        $scope.partners = data;
    });
}]);
