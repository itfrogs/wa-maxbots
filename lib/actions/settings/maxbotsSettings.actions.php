<?php
class maxbotsSettingsActions extends waViewAction
{
    public function preExecute()
    {
        if (!waRequest::isXMLHttpRequest()) {
            $this->setLayout(new maxbotsSettingsLayout());
        }
    }

    public function settingsSidebar()
    {

    }

    public function __settingsGeneral()
    {
        $message = 'Hello world!';
        $this->view->assign('message', $message);
    }
}
