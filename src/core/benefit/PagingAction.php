<?php
abstract class PagingAction extends Action {
    const BEAN_PAGING_KEY = 'paging';
    const BEAN_PAGING_SCOPE = 0;
    public function init() {
        parent::init();
        $beans  = &$this->beans;
        $paging = new Paging();
        $paging->build( (Config::PAGING_SCOPE) ? Config::PAGING_SCOPE : PagingAction::BEAN_PAGING_SCOPE );
        $pkey   = (Config::PAGING_KEY)?Config::PAGING_KEY:PagingAction::BEAN_PAGING_KEY;

        $beans  = array_merge($beans,array($pkey=>$paging));
    }
}
?>
