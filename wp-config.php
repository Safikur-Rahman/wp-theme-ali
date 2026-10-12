<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'aliwordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'NDC{e0~%;A,pn~c|=]sd=eEv@c7v2&VTUnR)X0/u_ddBeEXeZ -#@pRZ_rG]E2ZU' );
define( 'SECURE_AUTH_KEY',  'N+|OP$G5Z_2aPj!^#s5wo9xsC-P+>?#[>8bNMoU[]*bxNHrG/}Lf{)1)]R?:x}Rk' );
define( 'LOGGED_IN_KEY',    '{DKG@iX]B6S-az*2Ty+Q8;=Il&iVv_w w[o~U0lu8{`[vaGAz:`r7c?PNa+mB=&J' );
define( 'NONCE_KEY',        ']F$xQ@_dUr^t;)+tN4_l)w@zv:;^!U7@@1QGpN1Xyv88IbP{lwXSfvT@~_dgj2U ' );
define( 'AUTH_SALT',        'fmt|$AM[@AtlOWN7Zu%g`]L#) i#06r} PdypfM8YOr-3,m8K[u9$;h{$}]noEf`' );
define( 'SECURE_AUTH_SALT', '6XnvNYVq7f<^iW49S2?Y6CY^L^XK]t!HRy_,pc@jM(FC.)B=f#N`>/H6jvX;.dg5' );
define( 'LOGGED_IN_SALT',   '/Vk)4Y8$X>pFq#:I4-L[4;fl(]/jw}Ir?`.|amW(%Q&1i{XfsMyoB[WWmu;w-B>>' );
define( 'NONCE_SALT',       'qltQvX0ROk87nQZ(>wA#?%zMRSUOdm2p9B&Fc}^5!Kt,F`>mUajDc6Q@%<}*7l=S' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
