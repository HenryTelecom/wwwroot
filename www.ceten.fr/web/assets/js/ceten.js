/******************************
 * 
 * CETEN services header CSS
 * 
 * @author Jonathan ARNAULT
 * @copyright Cercle des Élèves de TELECOM Nancy <bde@telecomnancy.net>
 * @license MIT
 * 
 *****************************/

/****************************************
 *
 * Ceten objects
 * 
 ***************************************/

if (!window.ceten) {
    window.ceten = {};
}

/**
 * Add assets from cdn
 * @param  string uri Asset URI
 */
ceten.cdn = function (uri) {
    return this.cdnPrefix + uri;
};

/**
 * URI function
 * @param  string uri Page URI
 */
ceten.uri = function(uri) {
    return this.routePrefix + uri;
};
