<?php
/**
 * Application and database options
 *
 * @author Marko Martinović
 */

/* Application name and version */
define('APP_NAME', 'Ajax Calculator');
define('APP_VER', '1.00');

/* Database */
define('DBMS', 'mysql');
define('DB_NAME', 'calculator');
define('DB_USER', 'calculator');
define('DB_PASSWORD', 'calculator');
define('DB_HOST', 'localhost');

/* Table names */
define('DB_TABLE_RESULTS', 'results');
define('DB_TABLE_STATS', 'statistics');

/* Error reporting to maximum */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

try {
    /* Setup database connection */
    $db_con = 
    new PDO(DBMS.':dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASSWORD,
        array(
            /* Force MySQL PDO driver to use UTF-8 for the connection */
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',

            /* Set the error reporting to exception */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
    
    /* Create results table. Use UNIQUE KEY for term to easily avoid 
     * duplicates using ON DUPLICATE KEY UPDATE ... */
    $db_con->query(
        'CREATE TABLE IF NOT EXISTS '.DB_TABLE_RESULTS.' (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            term VARCHAR(120) NOT NULL,
            result FLOAT NOT NULL,
            PRIMARY KEY(id),
            UNIQUE KEY(term)
        ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8, COLLATE utf8_general_ci;'
    );
    
    /* Create statistics table. Use UNIQUE KEY for result to easily avoid 
     * duplicates using ON DUPLICATE KEY UPDATE ... */
    $db_con->query(
        'CREATE TABLE IF NOT EXISTS '.DB_TABLE_STATS.' (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            result FLOAT NOT NULL,
            count INT UNSIGNED NOT NULL,
            PRIMARY KEY(id),
            UNIQUE KEY(result)
        ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8, COLLATE utf8_general_ci;'
    );
} catch(PDOException $e){
    // Catch database related errors  
    echo $e->getCode().' : '. $e->getMessage().
        ' on the line '. $e->getLine(). ' in the file '.
        $e->getFile(); exit;
} 
?>
