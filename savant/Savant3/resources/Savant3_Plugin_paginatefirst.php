<?php
/**
 * Description of Savant3_Plugin_paginateprev
 *
 * @author Marko
 */
class Savant3_Plugin_paginatefirst extends Savant3_Plugin{
    
    public function paginatefirst(array $params = array()) {
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $Savant->trigger_error("paginate_first: missing SavantPaginate class");
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $Savant->trigger_error("paginate_first: SavantPaginate is not initialized, use connect() first");
            return;
        }
        
        /* Text can be set in two ways: First way is using
         * setPluginConf('paginatefirst',array('text' => 'First')); */
        $_text = isset($this->text) ? $this->text : SavantPaginate::getFirstText($_id);

        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $Savant->trigger_error("paginate_first: unknown id '$_val'");
                        return;
                    }
                    $_id = $_val;
                    break;

                /* Text can be set in two ways: Second way (takes precedence) is
                 *  passing array argument inside template like this
                 *  paginatefirst(array('text' => 'First')) */
                case 'text':
                    $_text = $_val;
                    break;
            }
        }

        if (SavantPaginate::getTotal($_id) === false) {
            $Savant->trigger_error("paginate_first: total was not set");
            return;
        }

        if(($_item = SavantPaginate::_getPrevPageItem($_id)) !== false) {
            $_link = true;
            $_url = SavantPaginate::getURL($_id);
            $_url .= (strpos($_url, '?') === false) ? '?' : '&';
            $_url .= SavantPaginate::getUrlVar($_id) . '=1';
        } else {
            $_link = false;
        }

        if($_link === true){
            return '<li><a href="' . str_replace('&','&amp;', $_url) . '">' . $_text . '</a></li>';
        } else {
            return  '<li class="off">'.$_text.'</li>';
        }
    }
}
?>
