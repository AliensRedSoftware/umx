<?php
namespace app\forms;

use std, gui, framework, app;

class mkdir extends AbstractForm {

    /**
     * @event name.globalKeyDown-Enter 
     */
    function doNameGlobalKeyDownEnter(UXKeyEvent $e = null) {    
        if (trim($e->sender->text) == null) {
            UXDialog::showAndWait('Создать без название папку низя' , 'ERROR');
            return;
        } else {
            $this->mkdir($e->sender->text);
            $this->hide();
        }
    }
}
