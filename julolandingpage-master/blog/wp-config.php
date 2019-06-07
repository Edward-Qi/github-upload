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
define('DB_NAME', 'julw9847_blog');

/** MySQL database username */
define('DB_USER', 'julw9847_admin');

/** MySQL database password */
define('DB_PASSWORD', '1.mF@@~cCPo#');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('FORCE_SSL_ADMIN', false);

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Ew%-OLq:qwz;T9Xr(yF9sr{u9nzjh8P-@/#AOWk&_&<_wO@&Ah=;g!#m/}?1PXB=');
define('SECURE_AUTH_KEY',  'EV2KiP4,LFE2h_o5Fkedo:.PKv+oW{9PT([XQauh.FW:@s}2-gCr)BFi/n#=(jm;');
define('LOGGED_IN_KEY',    'xC^z/?dNiKP^<;7Vo,+=oQ`;Imo$Y`8p[q_JoYgv58@bZG<I>kgs8>!4URwgB.(p');
define('NONCE_KEY',        '~C:N:nOdG!m88W-JhRWW:Pu3 /)K(L=h,9k(Nr~I<=v:P`Bw[@W>%k?~q@oMgjqY');
define('AUTH_SALT',        '#&iuxo;GZ8]#SdBiEi5:<Dh<pqVp0ZLVRI8,Bym+fwxPizhTT$e,}UV$H1b(xn}u');
define('SECURE_AUTH_SALT', 's&OM%Lk)>^es0`@(?,2G^&Gb0}vN{J5xE)!*<^Yi7;QI?_H0*8bihS=O*AT!y1fj');
define('LOGGED_IN_SALT',   '6oBh:9*JA,5f@ xEzoBa!K+)?E;(q<ZhW1k:g;1-,Z]z82)KS>$Lmhd7cGSD`JVl');
define('NONCE_SALT',       '{Q,F-(~hQ]dK5mM_}.Mgc kfOL~cC(r-* `{C$UbJ:&Z18{j{;twW`N%9lyTDyxm');
define( 'WP_DEBUG', true );

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
