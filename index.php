<?php
/**
 * Application entry point
 *
 * @author Marko Martinović
 */

// Initialize application
require_once('init.php');

// Include Savant template engine
require_once('savant/Savant3.php');

// Create new Savant object to use Savant templating engine
$tpl = 
new Savant3(
    array(
        // Set template path
        'template_path' => 'templates'
        )
    );

// Display our only template
$tpl->display('index.tpl.php');
?>
