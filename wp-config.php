<?php
/**
* Configuración básica de WordPress.
*
* Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
* claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
* visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
* wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
*
* This file is used by the wp-config.php creation script during the
* installation. You don't have to use the web site, you can just copy this file
* to "wp-config.php" and fill in the values.
*
* @package WordPress
*/

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'qqw269');

/** Tu nombre de usuario de MySQL */
//define('DB_USER', 'qqw269');
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
//define('DB_PASSWORD', 'M22elgato');
define('DB_PASSWORD', '');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
//define('DB_HOST', 'qqw269.pensionadeco.com');
define('DB_HOST', 'localhost');


/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
* Claves únicas de autentificación.
*
* Define cada clave secreta con una frase aleatoria distinta.
* Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
* Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
*
* @since 2.6.0
*/
define('AUTH_KEY', 'ZD$Q>B_TIEM=UOP2-E{ZdhC|^<sE##%kK6kBK.TnKU<C,.?;;7}j5*H Xe/9z~<<'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_KEY', '=XII)VvZ6),QdVKRgS|j(j|D3|!FaT+(@j7IjY+[Y*t/}{fv5}h&9-ecx*u9j<!p'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_KEY', '^@S-myw_CHsVs;v,/l2|45U&ddDn+;)TRM/8@tU8ic)]_i?TlIsV+R.-*VxMxDyp'); // Cambia esto por tu frase aleatoria.
define('NONCE_KEY', 'mjO[[E~*p<9NM@:5-iP9waL:nGH-:x~A|y|3W,.xX]#,+cfRWVH$e>Rk:ey+O1uB'); // Cambia esto por tu frase aleatoria.
define('AUTH_SALT', 'm4UO_M.RX*== 2fB-|3Yu&*qk@e~upXLJ:^p9Q+&62y,|!X/ME$&2W9G %-$9tV9'); // Cambia esto por tu frase aleatoria.
define('SECURE_AUTH_SALT', 'ojo+-vdY-W/Nbd-E+)ky]w[`,)b~6-u=}CR.qNo0HTK8>pl!{AtVE`iTRv(a+t;9'); // Cambia esto por tu frase aleatoria.
define('LOGGED_IN_SALT', 'l}c NiyXe{|8u84_V7A} DE$ Pb oD=IKFXfu)L>C?hT/%o`?0=pe17w?%^MKYn6'); // Cambia esto por tu frase aleatoria.
define('NONCE_SALT', '[k#i^s|26_T/ZOZEayYaEg}r+@)#OTS_[jDV>_4|-J->;T%_5p-k{#_?7fbe5O /'); // Cambia esto por tu frase aleatoria.

/**#@-*/

/**
* Prefijo de la base de datos de WordPress.
*
* Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
* Emplea solo números, letras y guión bajo.
*/
$table_prefix  = 'wp_';

/**
* Idioma de WordPress.
*
* Cambia lo siguiente para tener WordPress en tu idioma. El correspondiente archivo MO
* del lenguaje elegido debe encontrarse en wp-content/languages.
* Por ejemplo, instala ca_ES.mo copiándolo a wp-content/languages y define WPLANG como 'ca_ES'
* para traducir WordPress al catalán.
*/
define('WPLANG', 'es_ES');

/**
* Para desarrolladores: modo debug de WordPress.
*
* Cambia esto a true para activar la muestra de avisos durante el desarrollo.
* Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
* en sus entornos de desarrollo.
*/
define('WP_DEBUG', true);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

