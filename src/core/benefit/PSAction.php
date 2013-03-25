<?php
abstract class PSAction extends Action {
    public function init() {
        parent::init();
        $beans  = &$this->beans;
        $paging = new Paging();
        $paging->build( (Config::PAGING_SCOPE) ? Config::PAGING_SCOPE : PagingAction::BEAN_PAGING_SCOPE );
        $pkey   = (Config::PAGING_KEY)?Config::PAGING_KEY:PagingAction::BEAN_PAGING_KEY;

        $search = new Search();
        $search->build( (Config::SEARCH_SCOPE) ? Config::SEARCH_SCOPE : SearchAction::BEAN_SEARCH_SCOPE );
        $skey   = (Config::SEARCH_KEY)?Config::SEARCH_KEY:SearchAction::BEAN_SEARCH_KEY;

        $beans  = array_merge($beans,array($pkey=>$paging,$skey=>$search));
    }
}
?>
