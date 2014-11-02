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
    
    $http.get(ceten.uri('/produits.json')).success(function(data) {
        $scope.products = data;
    });

    $http.get(ceten.uri('/categories.json')).success(function(data) {
        $scope.tags = data;
    });

    $scope.productDetail = function (product) {
        console.log(product);
        ngDialog.open({
            template: '/partials/product/detail.html',
            controller: 'ProductDetailCtrl',
            data: { product: product }
        });
    };
}]);


/**
 * Products controller
 */
cetenControllers.controller('ProductDetailCtrl', ['$scope', '$http', '$routeParams', '$rootScope',
  function ($scope, $http, $routeParams, $rootScope) {
    $http.get(ceten.uri('/produits/' + $scope.ngDialogData.product.slug + '.json')).success(function(data) {
        $scope.product = data;
    });
}]);


/**
 * Tags controllers
 */
cetenControllers.controller('TagDetailCtrl', ['$scope', '$http', '$routeParams', '$rootScope', 'ngDialog',
  function ($scope, $http, $routeParams, $rootScope, ngDialog) {
    $http.get(ceten.uri('/categories/' + $routeParams.slug + '.json')).success(function(data) {
        $rootScope.title = data.name;
        $scope.tag = data;
    });

    $scope.productDetail = function (product) {
        ngDialog.open({
            template: '/partials/product/detail.html',
            controller: 'ProductDetailCtrl',
            data: { product: product }
        });
    };
}]);


/**
 * Cart controllers
 */
cetenControllers.controller('CartCtrl', ['$scope', '$http', '$routeParams', '$rootScope',
  function ($scope, $http, $routeParams, $rootScope) {
    $rootScope.title = 'Panier';

    $http.get(ceten.uri('/panier.json')).success(function(data) {
        $scope.orders = data;

        ceten.cart.load();
    });

    $scope.$on('cart.orders', function (e, orders) {
        if (orders == undefined) {
            return;
        }
        setTimeout(function () {
            $scope.$apply(function () {
                $scope.orders = orders;
            });
        }, 1);
    });
}]);

