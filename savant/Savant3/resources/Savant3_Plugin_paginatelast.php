<?php
/**
 * Description of Savant3_Plugin_paginateprev
 *
 * @author Marko
 */
class Savant3_Plugin_paginatelast extends Savant3_Plugin{
    
    public function paginatelast(array $params = array()) {
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $Savant->trigger_error("paginate_last: missing SavantPaginate class");
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $Savant->trigger_error("paginate_last: SavantPaginate is not initialized, use connect() first");
            return;
        }

        /* Text can be set in two ways: First way is using
         * setPluginConf('paginatelast',array('text' => 'Last')); */
        $_text = isset($this->text) ? $this->text : SavantPaginate::getLastText($_id);

        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $Savant->trigger_error("paginate_last: unknown id '$_val'");
                        return;
                    }
                    $_id = $_val;
                    break;

                /* Text can be set in two ways: Second way (takes precedence) is
                 *  passing array argument inside template like this
                 *  paginatelast(array('text' => 'Last')) */
                case 'text':
                    $_text = $_val;
                    break;
            }
        }

        if (SavantPaginate::getTotal($_id) === false) {
            $Savant->trigger_error("paginate_last: total was not set");
            return;
        }

        $_url = SavantPaginate::getURL($_id);
        $_total = SavantPaginate::getTotal($_id);
        $_limit = SavantPaginate::getLimit($_id);

        if(($_item = SavantPaginate::_getNextPageItem($_id)) !== false) {
            $_link = true;
            $_url .= (strpos($_url, '?') === false) ? '?' : '&';
            $_url .= SavantPaginate::getUrlVar($_id) . '=';
            $_url .= ($_total % $_limit > 0) ? $_total - ( $_total % $_limit ) + 1 : $_total - $_limit + 1;
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
