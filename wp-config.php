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
define('DB_NAME', 'qqw269');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '&vQ7ekFtcYcPW-hrou$%-(Ht.v;Y-&J8DT=|-r~>3V8]N-HT[?~0y4MBMh%4a8+$');
define('SECURE_AUTH_KEY',  '#?}+-6(M1sec~kvrHXd|Hh<nv5Q^W)tZzBXPJ8I>qRJzH0%^(N]r-+CsDu^f$8K&');
define('LOGGED_IN_KEY',    ' BW[_rw^CHG,JWv5}n&SY)k.gLK^&E[Dz)=q)]/!}iSjig=op+ik2GKc8;S$Y}H<');
define('NONCE_KEY',        'e+x`T$48?c;B~?Dc]o=b6*%+Zl1=[k<!M FqULQe2QDg7gLi+ka?+hjB].?-7nAP');
define('AUTH_SALT',        'CiY3-#Bvx0MRzVGZ|5c0Bc<X;#V0A+^*{,FHBb|-9@UJ`8,w=kh1zd)O,_}5/NQH');
define('SECURE_AUTH_SALT', 'h_Lgv)E96eg<5l4X[2v[@uSe#@zikhl<$]1^l^lz-~Fv&>t0>Ti/ xoMmy]jg>/>');
define('LOGGED_IN_SALT',   '^jls_ PCYdat<{:;Mrc>5q|8 #Krb[D{w.>#?oEn&3o>r)E`>13P@FsU .?hz-BQ');
define('NONCE_SALT',       'nauO-#__f QXK&RE|vdqDt5=TtTKc2P`oZWx>K/4Yo!b;E8c|$-*Q>Yo8-L|9-@r');

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
