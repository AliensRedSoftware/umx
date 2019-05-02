<?php
namespace app\modules;

use std, gui, framework, app;

class AppModule extends AbstractModule {

    /**
     * @event action 
     */
    function doAction(ScriptEvent $e = null) {
        if (System::getProperty('os.name') == 'Linux') {
            $GLOBALS['cmd'] = 'xterm -l -lf log -e ./';
            $GLOBALS['file_execute'] = 'adb';
            $GLOBALS['space'] = null;
        } else {
            $GLOBALS['cmd'] = 'cmd /c ' ;
            $GLOBALS['file_execute'] = 'adb.exe';
            $GLOBALS['space'] = ' >> log';
        }
        $this->dircreated();
    }
    
    protected function dircreated () {
        if (fs::isDir('disk') == null) {
            mkdir('disk' , 0777);
        }
        if (fs::isDir('disk/img') == null) {
            mkdir('disk/img' , 0777);
        }
        if (fs::isDir('disk/file') == null) {
            mkdir('disk/file' , 0777);
        }
    }
}