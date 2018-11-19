<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'reidataconnect' );

/** MySQL database username */
define( 'DB_USER', 'reidataconnect' );

/** MySQL database password */
define( 'DB_PASSWORD', 'rWAVcT2A' );

/** MySQL hostname */
//define( 'DB_HOST', '35.225.133.47' );
define( 'DB_HOST', ':/cloudsql/rei-data-connect:us-central1:reidataconnectsql-instance' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'vU@;}|79!j{NmuSi|-Maj?MY)2uEY+4rp[kAquA3xhsRlr.|:<`<3)81~cCTsl-6');
define('SECURE_AUTH_KEY',  '/[C>tL&t%1]x~@<+&9[8Oc6HsF++20?l]Zn~5sO?{@?m![[MLU~4R^|RlvD-|YT]');
define('LOGGED_IN_KEY',    '#<VwQdEh$|uJ#/(OmtYKIZZd:?v/!,^EHl>|H~1CL$>?UzGA.V;xJn%=OZjAe7?E');
define('NONCE_KEY',        'D!wZw>sJI~~=GGr&oa~MaTrSn]q{z#l,rc%ClFYNq=4+}pg;aJs%q{@]+58!5dU=');
define('AUTH_SALT',        '?@g(]95)Y`]o>/>*|kQOgSf-k3Ndb%+MiYpC>3`$J)n;535AiF5n,NAw::gV-o8/');
define('SECURE_AUTH_SALT', '`T~,r(Hsnxkons z2=n8Y3>d&COK>dp ,r4R6F[ft!+bWwSRa]<rOkhgUDq`sn?-');
define('LOGGED_IN_SALT',   '.|`kpBW T,2h|Akllmjnh[ur&|:U2$1rDq_33~gkU-|eH#!em.c_uuiO)dp=I:W%');
define('NONCE_SALT',       '-Al%}EDrih|o&MW!)fN3mZb!|b.cGdITV6`6eu.@:w>>H-x#ZZ#3PNyrSJ<CS^d~');

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
