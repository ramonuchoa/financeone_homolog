<?php
/**
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */



define('DB_NAME', 'finance_one');
define('DB_USER', 'root');
define('DB_PASSWORD', 'db_f1n4nc30n3');
define('DB_HOST', 'financeone-homol.c0mlxseuzdzh.us-east-1.rds.amazonaws.com');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

define('AUTH_KEY',         'stoGD9524320kC68pcp5ZUFXp7djgg');
define('SECURE_AUTH_KEY',  '5zlzxsAx0DwA5wbS7Jh9PmWc6ZT16L');
define('LOGGED_IN_KEY',    'DUG9NJXOJef1b2WWUAG3bJ6XFmO6Go');
define('NONCE_KEY',        '53nX2L8n0156S887w41Pb59hfkl7gZ');
define('AUTH_SALT',        'v59F2e6976p3hW1cly01Z8btzBFg1k');
define('SECURE_AUTH_SALT', 'X9gZN1lEQ7755Jzv68vK3k55M5PSJv');
define('LOGGED_IN_SALT',   'I35doJ89b82F4HL8P0iZWf3209YSCB');
define('NONCE_SALT',       'h98Z7Ecq996s91nQ31l1It42j0kl7f');

$table_prefix  = 'wp_';

define('WP_DEBUG', false);


/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
        define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
