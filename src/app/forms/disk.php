<?php
namespace app\forms;

use std, gui, framework, app;

class disk extends AbstractForm {

    /**
     * @event hide 
     */
    function doHide(UXWindowEvent $e = null) {
        app()->getForm(MainForm)->hidePreloader();
    }

    /**
     * @event listfile.click-2x 
     */
    function doListfileClick2x(UXMouseEvent $e = null) {    
        $this->cd($e->sender->selectedItem);
    }

    /**
     * @event path.globalKeyDown-Enter 
     */
    function doPathGlobalKeyDownEnter(UXKeyEvent $e = null) {    
        $this->cd($e->sender->text);
    }


    /**
     * @event path.step 
     */
    function doPathStep(UXEvent $e = null) {    
        if (!str::startsWith($e->sender->text , '/')) {
            $text = $e->sender->text;
            $this->path->clear();
            $this->path->text = '/' . $text;
        }
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null) {    
        $this->getdir();
    }

    /**
     * @event sendfile.action 
     */
    function doSendfileAction(UXEvent $e = null) {    
        $this->sendFileToDisk($this->path->text);
    }

    /**
     * @event deletefile.action 
     */
    function doDeletefileAction(UXEvent $e = null) {    
        if (!str::endsWith($this->path->text , '/')) {
            $this->deleteFile($this->path->text . '/' . $this->listfile->selectedItem , true);
        } else {
            $this->deleteFile($this->path->text . $this->listfile->selectedItem , true);
        }
    }



    /**
     * @event mkdir.action 
     */
    function doMkdirAction(UXEvent $e = null) {    
        $this->showPreloader('Ожидание ответа от формы...');
        app()->getForm(mkdir)->show();
    }

    /**
     * @event downloadAllImage.action 
     */
    function doDownloadAllImageAction(UXEvent $e = null) {    
        $this->downloadAllImg();
    }

    /**
     * @event upload.action 
     */
    function doUploadAction(UXEvent $e = null) {    
        $this->uploadFile('disk/file');
    }

    /**
     * @event listfile.action 
     */
    function doListfileAction(UXEvent $e = null) {    
        $this->openfile($this->path->text , $e->sender->selectedItem);
    }

    /**
     * @event back.action 
     */
    function doBackAction(UXEvent $e = null) {
        $this->backdir();
    }

    /**
     * @event home.action 
     */
    function doHomeAction(UXEvent $e = null) {
        $this->path->clear(); 
        $this->cd('');
    }
}
