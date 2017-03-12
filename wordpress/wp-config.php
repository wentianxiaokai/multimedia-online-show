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
define('DB_NAME', 'online_show');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1:3306');

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
define('AUTH_KEY',         '^td|{,,b]l6ty$fY|c0|f}8ew1#sf[p1l2ZR+8tRCn{T~`9$9P;i!:Ye{mJ]HeQ}');
define('SECURE_AUTH_KEY',  'i^|/CpeM7$(aNYSVH 0<CcqUbR7r2_Cm>BV[R{?x:Li?@(|334tMfA Xmv@K?:66');
define('LOGGED_IN_KEY',    '&Ftqr+ag*+[Q^z14^~DE+r*$#bgW9}6yj?xm7,*xY3N@qp?mE;Y|$EWB}O`CBwCN');
define('NONCE_KEY',        ']dbbDJD$kv^#W:J7i|:/hV^;R)Xh0Lv(KofkPQ2mcU#?eMbkX:/^;9%grCp ulXP');
define('AUTH_SALT',        '+VQw R.8DyeYhkne}2n]TG0lO]l sPE:#v5i!}.21!YYuG@S<rKM8ErPx8Nf<seH');
define('SECURE_AUTH_SALT', 'Zui](A.[A)9ErQ/9@JCD6hi<f58>Ne+N`+l*Ap?p}F.^QI%M%ri+|,FG/|U~g{[e');
define('LOGGED_IN_SALT',   'bm[DE0PAnl}C;2q.4(`[Ux*R2$b I^-:tF1|`;0Y9WH^QQ0O #e`m+GY^@){yd`Z');
define('NONCE_SALT',       'I}M7P-1{y`C!XLuC,+`%v|d7Dpv4=.,Mq~1jE/~LUS*iF(cQ;7z;1/Hh?mN+$p-h');

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
