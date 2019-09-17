<?php
namespace app\forms;

use std, gui, framework, app;

class rms extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {    
        app()->getForm(MainForm)->hidePreloader();
    }

    /**
     * @event right.action 
     */
    function doRightAction(UXEvent $e = null) {
        $this->keyevent(22);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event left.action 
     */
    function doLeftAction(UXEvent $e = null) {
        $this->keyevent(21);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event down.action 
     */
    function doDownAction(UXEvent $e = null) {
        $this->keyevent(20);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event up.action 
     */
    function doUpAction(UXEvent $e = null) {
        $this->keyevent(19);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event Menu.action 
     */
    function doMenuAction(UXEvent $e = null) {
        $this->keyevent(82);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event home.action 
     */
    function doHomeAction(UXEvent $e = null) {
        $this->keyevent(3);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event execute.action 
     */
    function doExecuteAction(UXEvent $e = null) {
        $this->keyevent(66);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event screenshotupdate.action 
     */
    function doScreenshotupdateAction(UXEvent $e = null) {
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event explorer.action 
     */
    function doExplorerAction(UXEvent $e = null) {
        $this->keyevent(64);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event back.action 
     */
    function doBackAction(UXEvent $e = null) {    
        $this->keyevent(4);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event acceptkey.action 
     */
    function doAcceptkeyAction(UXEvent $e = null) {
        if ($this->keylisting->text == null) {
            $this->keyevent(28);
            $this->screenshot(false);
            $this->image->image = new UXImage('disk/screenshot.png');
            return ;
        }
        $str = str::split($this->keylisting->text,'');
        $event = null;
        foreach ($str as $value) {
            if ($value == 'я') {
                $event .= 'z';
            } elseif ($value == 'ф') {
                $event .= 'a';
            } elseif ($value == 'й') {
                $event .= 'q';
            } elseif ($value == 'ч') {
                $event .= 'x';
            } elseif ($value == 'ы') {
                $event .= 's';
            } elseif ($value == 'ц') {
                $event .= 'w';
            } elseif ($value == 'с') {
                $event .= 'c';
            } elseif ($value == 'в') {
                $event .= 'd';
            } elseif ($value == 'у') {
                $event .= 'e';
            } elseif ($value == 'м') {
                $event .= 'v';
            } elseif ($value == 'а') {
                $event .= 'f';
            } elseif ($value == 'к') {
                $event .= 'r';
            } elseif ($value == 'и') {
                $event .= 'b';
            } elseif ($value == 'п') {
                $event .= 'g';
            } elseif ($value == 'е') {
                $event .= 't';
            } elseif ($value == 'т') {
                $event .= 'n';
            } elseif ($value == 'р') {
                $event .= 'h';
            } elseif ($value == 'н') {
                $event .= 'y';
            } elseif ($value == 'ь') {
                $event .= 'm';
            } elseif ($value == 'о') {
                $event .= 'j';
            } elseif ($value == 'г') {
                $event .= 'u';
            } elseif ($value == 'б') {
                $event .= ',';
            } elseif ($value == 'л') {
                $event .= 'k';
            } elseif ($value == 'ш') {
                $event .= 'i';
            } elseif ($value == 'ю') {
                $event .= '.';
            } elseif ($value == 'д') {
                $event .= 'l';
            } elseif ($value == 'o') {
                $event .= 'щ';
            } elseif ($value == 'ж') {
                $event .= '";"';
            } elseif ($value == 'з') {
                $event .= 'p';
            } elseif ($value == 'э') {
                $event .= "\"'\"";
            } elseif ($value == 'х') {
                $event .= "[";
            } elseif ($value == 'ъ') {
                $event .= "]";
            } elseif ($value == ' ') {
                $event .= ' ';
            } else {
                $event .= $value;
            }  
        }
        $this->text($event);
        $this->screenshot(false);
        $this->image->image = new UXImage('disk/screenshot.png');
    }

    /**
     * @event power.action 
     */
    function doPowerAction(UXEvent $e = null) {    
        if (uiConfirm('Вы точно хотите офнуть устройства ?)')){
            $this->keyevent(26);
        }
    }

    /**
     * @event reboot.action 
     */
    function doRebootAction(UXEvent $e = null) {
        if (uiConfirm('Вы точно хотите перезапустить устройства ?)')){
            $this->shell('reboot');
        }
    }

    /**
     * @event rebootbootloader.action 
     */
    function doRebootbootloaderAction(UXEvent $e = null) {
        if (uiConfirm('Вы точно хотите перезапустить устройства (bootloader) ?)')){
            $this->shell('reboot bootloader');
        }
    }
}
