<?php
class maxbotsBackendActions extends waViewActions
{
    public function preExecute()
    {
        if (!waRequest::isXMLHttpRequest()) {
            $this->setLayout(new maxbotsDefaultLayout());
        }
    }

    public function defaultAction()
    {
        $message = _w('Platform for creating Max bots.');
        $this->view->assign('message', $message);

        if (PHP_VERSION_ID >= 80200) {
            $php_text = '<span style="color: green">Версия PHP '.PHP_VERSION.' подходит для работы приложения.</span>';
        }
        else {
            $php_text = '<span style="color: red">Версия PHP '.PHP_VERSION.' не подходит для работы приложения. Требуется PHP 8.2 или выше.</span>';
        }

        $this->view->assign('php_text', $php_text);
    }
}
