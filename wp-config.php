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
define( 'DB_NAME', 'test' );

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
define( 'AUTH_KEY',         'E%bCd3znuYl9x^Iw,^kUtB.a!g[mE5A`bo?c+{yXA0vDyhWk`.VLVLlC:u>=}SJu' );
define( 'SECURE_AUTH_KEY',  '7$7c+-0Tgc$tI&uBgLT:I =|hi,4nXUAvyo#+9j;Ug)0C2E{3x%F+;o/gnF0EdGV' );
define( 'LOGGED_IN_KEY',    ':@ITZ%=?Ue7s^7 E+= GK*{5xVHs6hh,U0@oQ cCJAXey*}v7(1k3dYaiQwS`ZYy' );
define( 'NONCE_KEY',        'v>zQeN(PGJdP[e=.NO7N@P149k[0Ay[CT18T,X1[cg9~n1H8b0VeRm,+aG/q4-b!' );
define( 'AUTH_SALT',        '84N`Sf(J[uEg{b.k,WAa;@Aot5&]rEkIfP%~gG5uyLn#^Ga`&dFR; IKu,~wkxrG' );
define( 'SECURE_AUTH_SALT', '|{[I2~DaoBDH64uIqd~d2[YWujlY//PC::`9A:~5h1!}f<@hh.wh{H7)umQL3kYu' );
define( 'LOGGED_IN_SALT',   'R53a//41W[qGnB_N;W<:4;Y@WNs^}8U?V!_$FUYY!y]@ll39N{tlxu0S>n5Se&fJ' );
define( 'NONCE_SALT',       'OrhZCf@bfrqTN2 l3y}?HGAQTf%%N%nzbXszt hhU1.>gpu]cN_!&k?;Q1[9/,wr' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
