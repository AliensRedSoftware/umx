<?php
namespace app\forms;

use std, gui, framework, app;

class apkmanager extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        app()->getForm(MainForm)->hidePreloader();
    }

    /**
     * @event uninstallapk.action 
     */
    function doUninstallapkAction(UXEvent $e = null) {
        $this->uninstallapk($this->listapk->selectedItem);
    }

    /**
     * @event installapk.action 
     */
    function doInstallapkAction(UXEvent $e = null) {
        $this->installapk();
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {    
        $this->getlistapk();
    }
}
