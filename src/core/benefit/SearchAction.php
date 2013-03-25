<?php
abstract class SearchAction extends Action {
    const BEAN_SEARCH_KEY = 'search';
    const BEAN_SEARCH_SCOPE = 0;
    public function init() {
        parent::init();
        $beans  = &$this->beans;
        $search = new Search();
        $search->build( (Config::SEARCH_SCOPE) ? Config::SEARCH_SCOPE : SearchAction::BEAN_SEARCH_SCOPE );
        $skey   = (Config::SEARCH_KEY)?Config::SEARCH_KEY:SearchAction::BEAN_SEARCH_KEY;

        $beans  = array_merge($beans,array($skey=>$search));
    }
}
?>
