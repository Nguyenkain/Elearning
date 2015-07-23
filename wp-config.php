<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'elearning');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123456qwertY');

/** MySQL hostname */
define('DB_HOST', '192.168.1.18');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define( 'WP_MEMORY_LIMIT', '128M' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']6e7{m-!)Tye-Bo(-j-GUj0=oGF9t;EsY ,H1qbJ*41[^p+dYJhBP]!sH1FwOvj/');
define('SECURE_AUTH_KEY',  '-&0c8#Qv= 6N+g-bj|-TpCYYGBE9>6(n2 k;)H+-G!Rhpe:cM0C=},H2a]j@(fTi');
define('LOGGED_IN_KEY',    '9?Ah87QY#o|DJyB%7v&R{X{x~hmX_%C^+|!IG+10h7ZC_.uuv^iD_|7#W?[tB^ef');
define('NONCE_KEY',        'Cr&if:[sVof-Q_Kf+_@]%[9!DIVZN}tcov+fC|eNZl(9&Qu)}EZ`PFhNl0-Qr9RB');
define('AUTH_SALT',        ')M]+B+j83EF^}1Xyk4ogK?#svbr$Vto&N;{MZq6L>SRQ5iq}.Tyb^Z_zz1}vo>D|');
define('SECURE_AUTH_SALT', ',e,t!&,26Yx*3~F(/ZzFoPhRMXq=Hw(,AF8b7Piz$8!8g{a)l_2eG`YAi9v8 !.Q');
define('LOGGED_IN_SALT',   'Ktmz8-_otpM-o~AnM2k> ~EjV>9Mwm`YhQ.#WcqAq~.H0M]E,;XTTZ2&2I(Vrw#U');
define('NONCE_SALT',       'UN=V_GXh=;F]-F;6ypbJK@)0NlNw|QDyr|E(Fn(1#bL<13 O(/1.-O[<6@(v)O!@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
