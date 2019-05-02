<?php
namespace app\forms;

use std, gui, framework, app;

class viewscreenshot extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
        $form = app()->getForm(MainForm);
        fs::delete('disk' . fs::separator() . 'screenshot.png');
        $form->hidePreloader();
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {    
        $this->image->image = new UXImage('disk' . fs::separator() . 'screenshot.png');
    }
}
