/******************************
 * 
 * CETEN services header CSS
 * 
 * @author Jonathan ARNAULT
 * @copyright Cercles des Élèves de TELECOM Nancy <bde@telecomnancy.net>
 * @license MIT
 * 
 *****************************/

var cetenControllers = angular.module('cetenControllers', []);

cetenControllers.controller('IndexCtrl', ['$scope', '$http', '$routeParams', '$rootScope', 'ngDialog',
  function ($scope, $http, $routeParams, $rootScope, ngDialog) {
    $rootScope.title = 'Accueil';
    
}]);
