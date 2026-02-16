<?php
class maxbotsSettingsGeneralAction extends waViewAction
{
    public function preExecute()
    {
        if (!waRequest::isXMLHttpRequest()) {
            $this->setLayout(new maxbotsSettingsLayout());
        }
    }

    public function execute()
    {
        $message = 'Hello world!';
        $this->view->assign('message', $message);
    }
}
