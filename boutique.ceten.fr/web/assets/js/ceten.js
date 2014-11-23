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
 * Ceten cart utils
 * @type {Object}
 */
ceten.cart = {};
    
/**
 * Ceten product util
 * @type {Object}
 */
ceten.product = {};

/**
 * Product price
 * @param  {object} product
 * @param  {number} amount
 * @return {number}
 */
ceten.product.price = function (product, amount) {
    if (undefined == amount) {
        amount = 1;
    }

    if (undefined == product) {
        return '';
    }

    if (ceten.member) {
        return product.ceten_price * amount;
    }
    return product.price * amount;
};

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

/**
 * Display price
 * @param object product
 * @return {string}
 */
ceten.price = function (product) {
    if (undefined == product) {
        return '';
    }
    return ceten.product.price(product).toFixed(2) + '€';
};

/**
 * Orders total
 * @param  {object} orders
 * @return {number}
 */
ceten.total = function (order) {
    if (order == undefined || order.orders == undefined) {
        return;
    }
    
    var sum = 0,
        order;
    for (var i in order.orders) {
        o = order.orders[i];

        sum += ceten.product.price(o.product, o.count);
    }

    return sum.toFixed(2) + '€';
};

/**
 * Max stock
 * @param object product
 * @return {number}
 */
ceten.maxStock = function (product) {
    if (undefined == product) {
        return 0;
    }
    return (product.stock > this.maxOrder) ? this.maxOrder : product.stock;
};


/****************************************
 *
 * String Helpers
 * 
 ***************************************/

/**
 * Decode url
 * @return {string} URL decoded string
 */
String.prototype.urlDecode = function  () {
    return decodeURIComponent(this.toString()).replace(/\+/g, '%20');
};

/**
 * Encode url
 * @return {string} URL encoded string
 */
String.prototype.urlEncode = function  () {
    return encodeURIComponent(this.toString());
};


/**
 * Encode in base64
 * @source  PHPJS
 * @return {string} Base64 encoded string
 */
String.prototype.base64encode = function () {
    //    discuss at: http://phpjs.org/functions/base64_encode/
    // original by: Tyler Akins (http://rumkin.com)
    // improved by: Bayron Guevara
    // improved by: Thunder.m
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Rafał Kukawski (http://kukawski.pl)
    // bugfixed by: Pellentesque Malesuada
    //     example 1: base64_encode('Kevin van Zonneveld');
    //     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
    //     example 2: base64_encode('a');
    //     returns 2: 'YQ=='
    
    var data = this.toString();

    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        enc = '',
        tmp_arr = [];

    if (!data) {
        return data;
    }

    do { // pack three octets into four hexets
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1 << 16 | o2 << 8 | o3;

        h1 = bits >> 18 & 0x3f;
        h2 = bits >> 12 & 0x3f;
        h3 = bits >> 6 & 0x3f;
        h4 = bits & 0x3f;

        // use hexets to index into b64, and append result to encoded string
        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    var r = data.length % 3;

    return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

/**
 * Decode in base64
 * @source PHPJS
 * @return {string} Base64 decoded string
 */
String.prototype.base64decode = function () {
    //    discuss at: http://phpjs.org/functions/base64_decode/
    // original by: Tyler Akins (http://rumkin.com)
    // improved by: Thunder.m
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //        input by: Aman Gupta
    //        input by: Brett Zamir (http://brett-zamir.me)
    // bugfixed by: Onno Marsman
    // bugfixed by: Pellentesque Malesuada
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
    //     returns 1: 'Kevin van Zonneveld'
    //     example 2: base64_decode('YQ===');
    //     returns 2: 'a'

    var data = this.toString();

    var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
    var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
        ac = 0,
        dec = '',
        tmp_arr = [];

    if (!data) {
        return data;
    }

    data += '';

    do { // unpack four hexets into three octets using index points in b64
        h1 = b64.indexOf(data.charAt(i++));
        h2 = b64.indexOf(data.charAt(i++));
        h3 = b64.indexOf(data.charAt(i++));
        h4 = b64.indexOf(data.charAt(i++));

        bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

        o1 = bits >> 16 & 0xff;
        o2 = bits >> 8 & 0xff;
        o3 = bits & 0xff;

        if (h3 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1);
        } else if (h4 == 64) {
            tmp_arr[ac++] = String.fromCharCode(o1, o2);
        } else {
            tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
        }
    } while (i < data.length);

    dec = tmp_arr.join('');

    return dec.replace(/\0+$/, '');
}

/**
 * HTML special chars
 * @source PHPJS
 * @return {string} HTML encoded string
 */
String.prototype.htmlspecialchars = function(quote_style, charset, double_encode) {

    //             discuss at: http://phpjs.org/functions/htmlspecialchars/
    //            original by: Mirek Slugen
    //            improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //            bugfixed by: Nathan
    //            bugfixed by: Arno
    //            bugfixed by: Brett Zamir (http://brett-zamir.me)
    //            bugfixed by: Brett Zamir (http://brett-zamir.me)
    //             revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //                 input by: Ratheous
    //                 input by: Mailfaker (http://www.weedem.fr/)
    //                 input by: felix
    // reimplemented by: Brett Zamir (http://brett-zamir.me)
    //                         note: charset argument not supported
    //                example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
    //                returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
    //                example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
    //                returns 2: 'ab"c&#039;d'
    //                example 3: htmlspecialchars('my "&entity;" is still here', null, null, false);
    //                returns 3: 'my &quot;&entity;&quot; is still here'

    var string = this.toString();

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


/****************************************
 *
 * Object Helpers
 * 
 ***************************************/

/**
 * Return object values
 * @param  {oject} o
 * @return {array}
 */
ceten.values = function (o) {
    var values = [];
    for (var i in o) {
        if (o.hasOwnProperty(i)) {
            values.push(o[i]);
        }
    }
    return values;
}


/****************************************
 *
 * Cookie helpers
 * 
 ***************************************/

ceten.cookie = {};

/**
 * set a cookie
 * @param {string} name
 * @param {string} value
 * @param {number} expires Number of seconds before expiration
 */
ceten.cookie.set = function (name, value, expires) {
    var expires = '';
    if (expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires);
        expires = '; expires='+d.toUTCString();
    }
    document.cookie = name + "=" + value.urlEncode() + '; path=/' + expires;
};

/**
 * Get a cookie
 * @param  {string} name
 * @return {string} Cookie value or null if cookie does not exist
 */
ceten.cookie.get = function (name) {
    name = name + '=';
    var cookies = document.cookie.split("; "),
        cookie;

    for (var i in cookies) {
        if (cookies[i].indexOf(name) == 0) {
            return cookies[i].substr(cookies[i].indexOf('=')+1).urlDecode();
        }
    }
    return null;
};


/****************************************
 *
 * Array Helpers
 * 
 ***************************************/

/**
 * Create number range
 * @param  {number} start
 * @param  {number} end
 * @param  {number} step
 * @return {array}
 */
Array.range = function (start, end, step) {
    if (end < start) {
        return [];
    }

    if (!step) {
        step = 1;
    }

    if (step == 0) {
        return [];
    }

    var c = Math.ceil((end + 1 - start) / step);
    return Array.apply(null, Array(c)).map(function (_, i) {return i * step + start;});
}

ceten.range = Array.range;

/**
 * Array sum
 * @param  {array} a
 * @return {float|integer}
 */
ceten.sum = function (a) {
    var sum = 0;
    for (var i in a) {
        sum += a[i];
    }
    return sum;
}