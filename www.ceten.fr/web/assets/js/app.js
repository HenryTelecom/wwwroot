/******************************
 * 
 * CETEN CSS
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

htmlspecialchars = function (string, quote_style, charset, double_encode) {
  //       discuss at: http://phpjs.org/functions/htmlspecialchars/
  //      original by: Mirek Slugen
  //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      bugfixed by: Nathan
  //      bugfixed by: Arno
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //         input by: Ratheous
  //         input by: Mailfaker (http://www.weedem.fr/)
  //         input by: felix
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //             note: charset argument not supported
  //        example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
  //        returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
  //        example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
  //        returns 2: 'ab"c&#039;d'
  //        example 3: htmlspecialchars('my "&entity;" is still here', null, null, false);
  //        returns 3: 'my &quot;&entity;&quot; is still here'

  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined' || quote_style === null) {
    quote_style = 2;
  }
  string = string.toString();
  if (double_encode !== false) { // Put this first to avoid double-encoding
    string = string.replace(/&/g, '&amp;');
  }
  string = string.replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');

  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/'/g, '&#039;');
  }
  if (!noquotes) {
    string = string.replace(/"/g, '&quot;');
  }

  return string;
}

ceten.init = function ($rootScope) {
    
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
                .when(ceten.uri('/clubs'), {
                        templateUrl: '/partials/club/list.html',
                        controller: 'ClubListCtrl'
                    })
                .when(ceten.uri('/partenaires'), {
                        templateUrl: '/partials/partner/list.html',
                        controller: 'PartnerListCtrl'
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
            return $sce.trustAsHtml(htmlspecialchars(text, 'ENT_QUOTES').replace(/\r?\n/g, '<br>'));
        }
    }]);