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
    
    $http.get(ceten.uri('/produits.json')).success(function(data) {
        $scope.products = data;
    });

    $http.get(ceten.uri('/categories.json')).success(function(data) {
        $scope.tags = data;
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
 * Products controller
 */
cetenControllers.controller('ProductDetailCtrl', ['$scope', '$http', '$routeParams', '$rootScope',
  function ($scope, $http, $routeParams, $rootScope) {
    $scope.amount = 1;

    $http.get(ceten.uri('/produits/' + $scope.ngDialogData.product.slug + '.json')).success(function(data) {
        $scope.product = data;
    });

    $scope.setAmount = function (i) {
        $scope.amount = i;
    };
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
cetenControllers.controller('CartCtrl', ['$scope', '$http', '$routeParams', '$rootScope', '$window', 
  function ($scope, $http, $routeParams, $rootScope, $window) {
    $rootScope.title = 'Panier';

    $http.get(ceten.uri('/panier.json')).success(function(data) {
        $scope.order = data;

        ceten.cart.load();
    });

    $scope.type = 0;
    $scope.types = ['Paiement au retrait', 'Virement'];

    $scope.setType = function (type) {
        $scope.type = type;
    };

    $scope.sendOrder = function () {
        $http.post(ceten.uri('/panier/commander'), { type: $scope.type }).
            success(function(data, status, headers, config) {
                if (data['ok']) {
                    $window.location = ceten.uri('/');
                } else {
                    switch (data['code']) {
                        case 2:
                            alert('Vous devez être connecté pour pouvoir passer une commande.');
                            break;
                        case 3:
                            alert('Votre panier est vide.');
                            break;
                        case 1:
                            alert('Un des produits que vous avez commandé est en rupture de stock.');
                        default:
                            $window.location = ceten.uri('/');
                    }
                }
            });
    };

    $scope.$on('cart.orders', function (e, orders) {
        if (orders == undefined) {
            return;
        }
        setTimeout(function () {
            $scope.$apply(function () {
                $scope.order.orders = orders;
            });
        }, 1);
    });
}]);


/**
 * Orders controller
 */
cetenControllers.controller('OrderCtrl', ['$scope', '$http', '$routeParams', '$rootScope',
  function ($scope, $http, $routeParams, $rootScope) {

    $rootScope.title = 'Mes commandes';

    $scope.states = [
        'En attente de traitement',
        'En attente de retrait',
        'Retirée'
    ];

    $scope.payments = [
        'Non payé',
        'Carte Bleue',
        'Chèque',
        'Espèces',
        'Virement'
    ];

    $scope.date = function (date) {
        date = new Date(Date.parse(date));
        return date.toLocaleDateString() + ' à ' + date.toLocaleTimeString();
    };

    $http.get(ceten.uri('/mes-commandes.json')).success(function(data) {
        $scope.orders = data;
    });
}]);