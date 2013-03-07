<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gamping');

/** MySQL database username */
define('DB_USER', 'gamping');

/** MySQL database password */
define('DB_PASSWORD', 'v7zKu66Q');

/** MySQL hostname */
define('DB_HOST', 'mysql51-79.bdb');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         ']=+2+% |:Rbx~[rokUUXAigeqHFNsaB- 2rtyOq4uUj9`Z+;Mr<oak;dIBQErD]F');
define('SECURE_AUTH_KEY',  't0Q.4qWP[1(KPI&Itoy+0J3G6b;#E&SfEtP)v|cP!bK|b0n +)*Qh8++dp|+X]yC');
define('LOGGED_IN_KEY',    'bbk:#&Wo471ot}LjT[|[qL7Or-H#6gZ=%wzOF{w>+{6J5[OW8FM_0wP&&MG_z57Q');
define('NONCE_KEY',        '$V|t,Vn*lbv-DgFLnnl.hDCqS|j}RM/]p[kQ7bTdw}/a7& O2A]c,[dbMztt`3qH');
define('AUTH_SALT',        'ZE%Cs&4`G y@k?%y!{DO8VJhH*4veW^90Grb54$JRi&Rs:YFg[R{CR+y:KiB>L5&');
define('SECURE_AUTH_SALT', '|~neNM1W2mBn`db@a?2dAbx_2KpMDaTmmEmyIVAtI*_6DrCJt:/B/CPHu;zPr)uT');
define('LOGGED_IN_SALT',   'OjTrb!+#7ZR*f|qh~8.EkgkO`_#!P%|WkraM08-z7ES};l%t.[Y,tliGq_/n7X=z');
define('NONCE_SALT',       'hr2MO +5nyh{05-7|.8K;i/t`J|mfRLSf#9$ w:=K5F(G0lO#?]ZJ3N|C/%P.|6I');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

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
