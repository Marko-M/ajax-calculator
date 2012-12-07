<?php
/**
 * Singleton class for calculator
 *
 * @author Marko Martinović
 */
final class Calculator {

    private function __construct() {}

    /* Fetch database results matches for given term.
     * Returns array. */
    public static function get_results($term) {
        global $db_con;

        $term = self::sanitize_term($term);

        // If term empty after sanitize, abort
        if(empty($term))
            return array();

        $sql =
        'SELECT
            term,
            result
        FROM
            '.DB_TABLE_RESULTS.'
        WHERE
            term LIKE :term';

        $stmt=$db_con->prepare($sql);
        
        // Fe fetch terms starting with $term
        $stmt->bindValue(':term', $term.'%', PDO::PARAM_STR);
        $stmt->execute();

        // Return as associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Satnitize and tries to parse term,
     * returns false if not successful
     * and array with sanitized term and result
     * if successful.
     */
    public static function eval_term($term){
        $term = self::sanitize_term($term);

        // If term empty after sanitize, abort
        if(empty($term))
            return false;

        // Evaluates escaped term, if not successful, abort
        $result = @eval("return $term;");
        if($result === false)
            return false;

        // Term parsec successfully, insert into database
        self::insert_result($term, $result);
        
        // Return sanitized term and result
        return array($term, $result);
    }

    /* Inserts result row into results table
     * and statistics row into statistics table.
     */
    private static function insert_result($term, $result) {
        global $db_con;

        // Results table
        $sql =
        'INSERT INTO
            '.DB_TABLE_RESULTS.'
        SET
            term = :term,
            result = :result
        ON DUPLICATE KEY UPDATE id = id;';

        $stmt=$db_con->prepare($sql);
        $stmt->bindValue(':term', $term, PDO::PARAM_STR);
        $stmt->bindValue(':result', $result);
        $stmt->execute();

        // Statistics table
        $sql=
        'INSERT INTO
            '.DB_TABLE_STATS.'
        SET
            result = :result,
            count = 1
        ON DUPLICATE KEY UPDATE count = count + 1';

        $stmt=$db_con->prepare($sql);
        $stmt->bindValue(':result', $result);
        $stmt->execute();
    }

    /* Sanitize term */
    private static function sanitize_term($term) {
        // Remove all except numeric and accepted operators
        $term = preg_replace('/[^0-9\.\+\-\*\/\(\)]/', '', $term);
        
        // If there aren't of accepted operators found
        if(
            strpos($term, '+') === false &&
            strpos($term, '-') === false &&
            strpos($term, '*') === false &&
            strpos($term, '/') === false
        ) return false;
        
        return $term;
    }
}
?>
