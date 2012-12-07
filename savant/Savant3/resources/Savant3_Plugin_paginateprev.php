<?php
/**
 * Description of Savant3_Plugin_paginateprev
 *
 * @author Marko
 */
class Savant3_Plugin_paginateprev extends Savant3_Plugin{
    
    public function paginateprev(array $params = array()){
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Missing SavantPaginate class file'));
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'SavantPaginate is not initialized, use connect() first'));
            return;
        }

        /* Text can be set in two ways: First way is using
         * setPluginConf('paginateprev',array('text' => '&laquo;Previous')); */
        $_text = isset($this->text) ? $this->text : SavantPaginate::getPrevText($_id);


        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Unknown pagination id '.$_val));
                        return;
                    }
                    $_id = $_val;
                    break;

                /* Text can be set in two ways: Second way (takes precedence) is
                 *  passing array argument inside template like this
                 *  paginateprev(array('text' => '&laquo;Previous')) */
                case 'text':
                    $_text = $_val;
                    break;
            }
        }

        if (SavantPaginate::getTotal($_id) === false) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Total was not set'));
            return;
        }

        $_url = SavantPaginate::getURL($_id);

        if(($_item = SavantPaginate::_getPrevPageItem($_id)) !== false) {
            $_link = true;
            $_url .= (strpos($_url, '?') === false) ? '?' : '&';
            $_url .= SavantPaginate::getUrlVar($_id) . '=' . $_item;
        } else {
            $_link = false;
        }

        if($_link === true){
            return '<li class="previous"><a href="' . str_replace('&','&amp;', $_url) . '">' . $_text . '</a></li>';
        } else {
            return  '<li class="off">'.$_text.'</li>';
        }
    }
}
?>
