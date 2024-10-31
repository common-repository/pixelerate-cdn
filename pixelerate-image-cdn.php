<?php
/*
   Plugin Name: Pixelerate Image CDN
   Text Domain: pixelerate-image-cdn
   Description: Integrate the Pixelerate Image Optimization and CDN into your wordpress website.
   Author: TetraDigital
   Author URI: https://www.pixelerate.io
   License: GPLv2 or later
   Version: 1.0.0
 */

/*
   Copyright (C)  2019 TetraDigital

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License along
   with this program; if not, write to the Free Software Foundation, Inc.,
   51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/* Check & Quit */
defined('ABSPATH') OR exit;


/* constants */
define('PIXELERATE_CDN_FILE', __FILE__);
define('PIXELERATE_CDN_DIR', dirname(__FILE__));
define('PIXELERATE_CDN_BASE', plugin_basename(__FILE__));
define('PIXELERATE_CDN_MIN_WP', '4.0');


/* loader */
add_action(
    'plugins_loaded',
    [
        'Pixelerate_CDN',
        'instance',
    ]
);


/* uninstall */
register_uninstall_hook(
    __FILE__,
    [
        'Pixelerate_CDN',
        'handle_uninstall_hook',
    ]
);


/* activation */
register_activation_hook(
    __FILE__,
    [
        'Pixelerate_CDN',
        'handle_activation_hook',
    ]
);


/* autoload init */
spl_autoload_register('PIXELERATE_CDN_autoload');

/* autoload funktion */
function PIXELERATE_CDN_autoload($class) {
    if ( in_array($class, ['Pixelerate_CDN', 'Pixelerate_CDN_Rewriter', 'Pixelerate_CDN_Settings']) ) {
        require_once(
            sprintf(
                '%s/inc/%s.class.php',
                PIXELERATE_CDN_DIR,
                strtolower($class)
            )
        );
    }
}
