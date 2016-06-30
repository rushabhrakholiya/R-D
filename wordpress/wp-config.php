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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Dn[~+nud-2$/#I=+,ngT#zs+/LslcT@_?S,vb5Yj@Eav?qpf%mSmvzuiH1E)LB[[');
define('SECURE_AUTH_KEY',  'l07MeCs/<`8O]`awDRAfe+220Ygc*)IzJDpthTRZ<J1=:aZz8US_#t.p>]rKQ2Lf');
define('LOGGED_IN_KEY',    'l%WP~p@B/IsOYgh/BiaGn)}x1x]g-N+ex/p)h}_B-/>WCUXC@*&_u!inxQZ=]N!q');
define('NONCE_KEY',        'hvSksF:E/mki$Oq;m)(EgOngFugfB.;YPAEB^`AKkEO?|mS2b03kM*,&BsAokjMe');
define('AUTH_SALT',        'CF>>r(<>/1f@H-/(fQ:Q6mAtyr KCq_3<!5=s2C~H!~|YUSssKpg/K(9tmsPx50/');
define('SECURE_AUTH_SALT', '[Q/(-|s-Sk1`)}P/f{HZc_ *R]3ta7wFxu`c5S:TM8.>.KuF_Td;G~QQdL6OmwtK');
define('LOGGED_IN_SALT',   '<j=1{%]0ue7 Yy:mU!fbA o`1 pxmhu7-2eTR{d(JF:2$r?uuTADNwD]nZU;-X*D');
define('NONCE_SALT',       '}-JB&G5ad-bh=]2KX<8lEk{EV-N.Z,PFgF9`zLNH52]:>2FW=Ygh[T{0@@.PtxuD');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
if(is_admin()) {
	add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
	define( 'FS_CHMOD_DIR', 0751 );
}