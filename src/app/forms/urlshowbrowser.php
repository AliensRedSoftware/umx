<?php
namespace app\forms;

use std, gui, framework, app;

class urlshowbrowser extends AbstractForm {

    /**
     * @event url.globalKeyDown-Enter 
     */
    function doUrlGlobalKeyDownEnter(UXKeyEvent $e = null) {    
        if ($this->ssl->selected) {
            $this->browser('https://' . $e->sender->text);
        } else {
            $this->browser('http://' . $e->sender->text);
        }
        $e->sender->clear();
        $this->hide();
    }

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        app()->getForm(MainForm)->hidePreloader();
    }
}
