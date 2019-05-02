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
}
