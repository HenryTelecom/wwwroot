/******************************
 * 
 * CETEN services header CSS
 * 
 * @author Jonathan ARNAULT
 * @copyright Cercle des Élèves de TELECOM Nancy <bde@telecomnancy.net>
 * @license MIT
 * 
 *****************************/

(function ($) {
    $(function () {

        $(window).scroll(function () {
            var top = $('body').scrollTop() || $(window).scrollTop();
            top = 30 - top;
            if (top < 0) {
                top = 0;
            }

            $('.main-nav').css({ top: top });
        });
    });
})(jQuery);


ceten.init = function ($rootScope) {
    
    /**
     * Add a product to cart
     */
    ceten.cart.add = function (product, amount) {
        if (undefined == product) {
            return;
        }

        if (undefined == amount) {
            amount = 1;
        }

        if (!$rootScope.cart[product.id]) {
            $rootScope.cart[product.id] = 0;
        }
        $rootScope.cart[product.id] += amount;

        var stock = ceten.maxStock(product);
        if ($rootScope.cart[product.id] > stock) {
            $rootScope.cart[product.id] = stock;
        }

        ceten.cart.update();
    };


    /**
     * Set product stock
     * @param {object} product
     * @param {integer} stock
     */
    ceten.cart.set = function (product, stock, order) {
        if (undefined == product) {
            return;
        }

        var s = ceten.maxStock(product);
        if (stock <= s) {
            s = stock;
        }
        
        $rootScope.cart[product.id] = s;

        if (order) {
            order.count = s;
        }
        ceten.cart.update();
    }


    /**
     * Add a product to cart
     */
    ceten.cart.remove = function (order, orders) {
        if (order == undefined || orders == undefined) {
            return;
        }

        if (orders) {
            orders = orders.filter(function (o) {
                return o.product.id != order.product.id;
            });

            $rootScope.$broadcast('cart.orders', orders);
        }

        delete $rootScope.cart[order.product.id];
        ceten.cart.update();
    };


    /**
     * Update cookie
     */
    ceten.cart.update = function () {
        ceten.cookie.set(ceten.cartCookie, JSON.stringify($rootScope.cart).base64encode());
    };


    /**
     * Load cart
     */
    ceten.cart.load = function () {
        if (document.cookie) {
            $rootScope.cart = JSON.parse(ceten.cookie.get(ceten.cartCookie).base64decode()) || {};
        }
    }
};

var cetenApp = angular
    .module('cetenApp', [
            'ngRoute',
            'ngDialog',
            'cetenControllers'
        ])
    .config(['$routeProvider', '$locationProvider', 
        function($routeProvider, $locationProvider) {
            $routeProvider
                .when(ceten.uri('/'), {
                        templateUrl: '/partials/index.html',
                        controller: 'IndexCtrl'
                    })
                .when(ceten.uri('/categories/:slug'), {
                        templateUrl: '/partials/tag/detail.html',
                        controller: 'TagDetailCtrl',
                    })
                .when(ceten.uri('/panier'), {
                        templateUrl: '/partials/cart.html',
                        controller: 'CartCtrl',
                    })
                .when(ceten.uri('/mes-commandes'), {
                        templateUrl: '/partials/orders.html',
                        controller: 'OrderCtrl',
                    })
                .otherwise({
                        redirectTo: ceten.uri('/')
                    });
            $locationProvider.html5Mode({
                enabled: true,
                requireBase: false
            });
        }])
    .run( [ '$location', '$rootScope', function( $location, $rootScope ){
            ceten.init($rootScope);

            $rootScope.ceten = ceten;
            
            ceten.cart.load();
        }])
    .filter('sanitize', ['$sce', function($sce) {
        return function(html){
            return $sce.trustAsHtml(html);
        }
    }])
    .filter('nl2br', ['$sce', function($sce) {
        return function(text){
            if (undefined == text) {
                return '';
            }
            return $sce.trustAsHtml(text.htmlspecialchars().replace(/\r?\n/g, '<br>'));
        }
    }]);