<?php
namespace app\forms;

use std, gui, framework, app;

class MainForm extends AbstractForm {

    /**
     * @event disconnect.action 
     */
    function doDisconnectAction(UXEvent $e = null) {    
        $this->disconnectDevice($this->Devices->selectedItem);
    }

    /**
     * @event Devices.action 
     */
    function doDevicesAction(UXEvent $e = null) {
        if ($e->sender->selectedIndex == -1) {
            $this->shell->enabled = false;
            $this->disconnect->enabled = false;
            $this->packagemanager->enabled = false;
            $this->diskupload->enabled = false;
            $this->urlopen->enabled = false;
            $this->screenshot->enabled = false;
            $this->rms->enabled = false;
        } else {
            $this->shell->enabled = true;
            $this->disconnect->enabled = true;
            $this->packagemanager->enabled = true;
            $this->diskupload->enabled = true;
            $this->urlopen->enabled = true;
            $this->screenshot->enabled = true;
            $this->rms->enabled = true;
            $this->connectDevice($e->sender->selectedItem);
        }
    }

    /**
     * @event packagemanager.action 
     */
    function doPackagemanagerAction(UXEvent $e = null) {
        app()->getForm(apkmanager)->show();
        $this->showPreloader('Ожидание ответа от формы...');
    }

    /**
     * @event diskupload.action 
     */
    function doDiskuploadAction(UXEvent $e = null) {    
        app()->getForm(disk)->show();
        $this->showPreloader('Ожидание ответа от формы...');
    }

    /**
     * @event shell.action 
     */
    function doShellAction(UXEvent $e = null) {    
        $this->shell_show();
    }

    /**
     * @event urlopen.action 
     */
    function doUrlopenAction(UXEvent $e = null) {    
        app()->getForm(urlshowbrowser)->show();
        $this->showPreloader('Ожидание ответа от формы...');
    }

    /**
     * @event screenshot.action 
     */
    function doScreenshotAction(UXEvent $e = null) {    
        $this->screenshot(false);
        if (uiConfirm('Хотите открыть скриншот в этой программе ?')) {
            $this->showPreloader('Ожидание ответа от формы...');
            app()->getForm(viewscreenshot)->show();
        }
    }

    /**
     * @event rms.action 
     */
    function doRmsAction(UXEvent $e = null) {    
        app()->getForm(rms)->show();
        $this->showPreloader('Ожидание ответа от формы...');
    }

    /**
     * @event device.keyDown-Enter 
     */
    function doDeviceKeyDownEnter(UXKeyEvent $e = null) { 
        $this->connectDevice($e->sender->text);
    }

}
