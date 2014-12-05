/******************************
 * 
 * CETEN services header CSS
 * 
 * @author Jonathan ARNAULT
 * @copyright Cercles des Élèves de TELECOM Nancy <bde@telecomnancy.net>
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
 * URI function
 * @param  string uri Page URI
 */
ceten.uri = function(uri) {
    return this.routePrefix + uri;
};
