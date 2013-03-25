<?php
class DocAction extends Action {
    public function launch() {
        $result = &$this->result;
        $config = &$this->config;
        $docService = &$this->getService('DocService');
        $doc = $docService->read();
        $bean = new Doc();
        $bean->build($doc);
        echo "<pre>";
        $result->push($bean, 'result_doc');
        Debug::push($this->beans);
        echo "</pre>";
    }
}
?>
