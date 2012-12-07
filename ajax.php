<?php
/**
 * Singleton class for calculator
 *
 * @author Marko Martinović
 */

// Initialize application
require_once('init.php');

// Require our singleton class
require_once('classes/calculator.php');

if(isset($_GET['term'])){
    /* This is autocomplete ajax request to search for results */
    
    header( "Content-Type: application/json" );
    
    // Return empty array by default (no autocomplete entries found)
    $response =  array();    
    
    $term = $_GET['term'];
    try {
        // Search our results table for autocomplete entries
        $res = Calculator::get_results($term);
    } catch(PDOException $e){
        // Catch database related errors 
        echo $e->getCode().' : '. $e->getMessage().
            ' on the line '. $e->getLine(). ' in the file '.
            $e->getFile(); exit;
    }
    
    if(!empty($res)){
        // If any autocomplete entry exist, create autocomplete result
        foreach ($res as $r){
            array_push(
                $response,
                array(
                    'label' => $r['term'] . '='. $r['result'],
                    'value' => $r['term']
                )
            );
        }
    }
    
    // Return as JSON
    echo json_encode($response);
    exit;
}if (isset($_GET['calc'])) {
    /* Autocomplete didn't find any results, second ajax request
     * used to calculate result and update database */
    
    header( "Content-Type: application/json" );
    
    // Return empty array by default (term not parsable by eval)    
    $response =  array();
    
    $term = $_GET['calc'];
    try {
        // Try to evaluate term
        $r = Calculator::eval_term($term);
    } catch(PDOException $e){
        // Catch database related errors 
        echo $e->getCode().' : '. $e->getMessage().
            ' on the line '. $e->getLine(). ' in the file '.
            $e->getFile(); exit;
    }    
    if($r !== false){
        $response =
        array(
            'label' => $r[0] . '='. $r[1],
            'value' => $r[0]
        );       
    }

    // Return as JSON
    echo json_encode($response);
    exit;    
}
?>
