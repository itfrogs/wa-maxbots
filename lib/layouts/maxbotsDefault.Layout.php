<?php
class maxbotsDefaultLayout extends waLayout
{
    public function execute()
    {
        $this->executeAction('sidebar', new maxbotsSidebarAction());

        /**
         * Include plugins js and css
         * @event backend_assets
         * @return array[string]string $return[%plugin_id%] Extra head tag content
         */
        $this->view->assign('backend_assets', wa()->event('backend_assets'));
        $this->view->assign(array(
            'is_debug' => (int) waSystemConfig::isDebug(),
        ));
    }
}
