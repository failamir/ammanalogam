<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ammanalogam' );

/** Database username */
define( 'DB_USER', 'ammanalogam' );

/** Database password */
define( 'DB_PASSWORD', 'aKphfi10Bh4eBruhAsLR' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ' l@SyME%;?`)tkxM40|$x%PpKL>ZU1-Q.EDr-@~t|R:9U&ro]lZHzl[BV+0Z$;RD' );
define( 'SECURE_AUTH_KEY',   'kr8iUVaF.&aAUByXlTG[p<R7:iiw]=Dw7%v@y*hbK/2fKD9az#W`l#BS]|L&1N;<' );
define( 'LOGGED_IN_KEY',     'QS[{C$tnz=s<$99{V?YZlP0qfZyj+;Zpg{p%]P&]yBz/SA_1XVK]WSW][WYT9gpf' );
define( 'NONCE_KEY',         '@pA_rv4;3t:<$PP-rch&YfGyyYjPqwp/&(qRmS6B(^xVRCYQ+b=1MiqNaT3>=Z~h' );
define( 'AUTH_SALT',         'Sg<`rLUY*U9[om67xs&WjbFR$,<T8Z|TI:ZW!owL#O0<Mg^?n9/D13Vik]K]-2Gx' );
define( 'SECURE_AUTH_SALT',  '~q 3,&v3l*&%6%$=lS.2{JEt0l(sTLDTJ1EjhI$E8*eOsj~yge~Pb7S(MQniboxd' );
define( 'LOGGED_IN_SALT',    '<kbMug4=-+R3blNbzvLo(zxBgZ?FP1NEi]|J-%=4&G?dr6@k8F4NC&C.=ay2iFl!' );
define( 'NONCE_SALT',        '21C?Bj+ul,(gM~sD:JD[Cl!!E5,T}eie}LIy/JV=#k2pUxU%%3w!#L81ED9ri}=Y' );
define( 'WP_CACHE_KEY_SALT', 'JELJ;u986ZT4%~k)2%pgGn<Qj$a#Rf3@*_A#JgCZwh2%@[ p4^iRi p!C}yVTm[<' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
//$table_prefix = 'wp_';
$table_prefix = 'wpwc_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_DEBUG_LOG', true );
define( 'CONCATENATE_SCRIPTS', false );
define( 'AUTOSAVE_INTERVAL', 600 );
define( 'WP_POST_REVISIONS', 5 );
define( 'EMPTY_TRASH_DAYS', 21 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
