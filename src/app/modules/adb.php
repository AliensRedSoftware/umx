<?php
namespace app\modules;

use std, gui, framework, app;

class adb extends AbstractModule {


    /**
     * Подключение к устройству опеределенному
     */
    public function connectDevice ($ip) {
        $form = app()->getForm(MainForm);
        if ($ip == null) {
            UXDialog::showAndWait('Ошибка нужно ввести ip адрес устройства' , 'ERROR');
            return;
        }
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " connect $ip" . $GLOBALS['space'] , true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        $text = file_get_contents('log');
        if ($text == 'failed to connect to ' . $ip . ':5555' || $text == null) {
            UXDialog::showAndWait('Ошибка подключение устройства не найдено!' , 'ERROR');
            $form->hidePreloader();
            return;
        } else {
            UXDialog::showAndWait('Успешно подключилась устройства!' , 'INFORMATION');
            foreach ($form->Devices->items->toArray() as $value) {
                if ($value == $form->Devices->selectedItem) {
                    Logger::error('Устройства не будет добавлена так как она уже есть!');
                    $form->device->clear();
                    $form->hidePreloader();
                    return ;
                }
            }
            $this->addDevice($ip);
        }
        $form->device->clear();
        $form->hidePreloader();
    }
    
    /**
     * Добавить устройства в список устройств
     */
    public function addDevice ($ip) {
         $form = app()->getForm(MainForm);
         $form->Devices->items->add($ip);
    }   
    
    /**
     * Отключение устройства определенному
     */
    public function disconnectDevice ($ip) {
        $form = app()->getForm(MainForm);
        if ($ip == null) {
            UXDialog::showAndWait('Нужна выбрать устройства которую нужно отключить!' , 'ERROR');
            return;
        } else {
            $form->showPreloader('Ожидание запроса...');
            fs::delete('log');
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " disconnect $ip" . $GLOBALS['space'] , true);
            file_put_contents('log' , trim(Stream::getContents('log')));
            $text = file_get_contents('log');
            $form->hidePreloader();
            if ($text == "disconnected $ip") {
                UXDialog::showAndWait('Успешно отключилось устройства' , 'INFORMATION');
                $form->Devices->items->removeByIndex($form->Devices->selectedIndex);
                return;
            } else {
                UXDialog::showAndWait('Устройства не найдено!' , 'ERROR');
                return;
            }
        }
    }
    
    /**
     * Получить все подключенные устройства локальных
     */
    public function getDeviceslocal () {
        $form = app()->getForm(MainForm);
        if ($form->Devices->items->count == null) {
            return 0;
        }
    }        
    
    /**
     * Получение директорий
     */
    public function getdir () {
        $form = app()->getForm(disk);
        $form->listfile->items->clear();
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell ls " . $form->path->text . $GLOBALS['space'], true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        $form->hidePreloader();
        $list = str::split(file_get_contents('log'), "\n");
        foreach ($list as $value) {
            $i++;
            $form->listfile->items->add(trim($value));
        }
    }
     
    /**
     * Назад на 1 ступень
     */
    public function backdir () {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        $cd = str::split($form->path->text , '/');
        array_pop($cd);
        foreach ($cd as $list) {
            $path .= $list . '/';
        }
        $form->path->text = $path;
        $this->getdir();
        $form->hidePreloader();
    }  
     
    /**
     * Скриншот экрана устройства
     */
    public function screenshot ($confirm) {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        $this->shell('screencap /sdcard/screenshot.png');
        $this->uploadFile('disk' , '/sdcard/screenshot.png');
        $this->deleteFile('/sdcard/screenshot.png' , $confirm);
        $form->hidePreloader();
    }
    
    /**
     * Загрузить файл на свой диск
     */
    public function uploadFile ($path , $file = null) {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        if (!str::endsWith($form->path->text , '/')) {
            $input = $form->path->text . '/' . $form->listfile->selectedItem;
        } else {
            $input = $form->path->text . $form->listfile->selectedItem;
        }
        if ($file == null){
            $value = $form->listfile->selectedItem;
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " pull $input $path/$value" . $GLOBALS['space'] , true);
        } else {
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " pull $file $path" . $GLOBALS['space'] , true);
        }
        $form->hidePreloader();
        UXDialog::showAndWait('Успешно!' , 'INFORMATION');
    }  
    
    /**
     * Перейти в каталог
     */
    public function cd ($path) {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        if ($form->path->text == $path) {
           $cd = $form->path->text; 
        } else {
            $cd = $form->path->text . $path;
        }
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell cd $cd" . $GLOBALS['space'] , true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        if ($form->path->text == $path) {
           
        } else {
            $form->path->text .= $path . '/';
        }
        $this->getdir();
        $form->hidePreloader();
    }     
      
    /**
     * Удалить приложение
     */
    public function uninstallapk ($apk) {
        if (uiConfirm("Вы точно хотите удалить этот пакет ?")) {
            if ($apk != null) {
                $form = app()->getForm(apkmanager);
                $form->showPreloader('Ожидание запроса...');
                fs::delete('log');
                execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell pm uninstall -k --user 0 $apk" . $GLOBALS['space'] , true);
                $this->getlistapk();
                $form->hidePreloader();
                UXDialog::show("Успешно удалился => $apk" , 'INFORMATION');
            } else {
                UXDialog::showAndWait('Ошибка удалить ничего не возможно!' , 'ERROR');
                return ;
            }
        }
    }    
    
    /**
     * Удалить приложение
     */
    public function installapk () {
        $this->fileChooser->execute();
        $apk = $this->fileChooser->file;
        if ($apk == null) {
            return;
        }
        if (uiConfirm("Вы точно хотите это установить ?")) {
            $form = app()->getForm(apkmanager);
            $form->showPreloader('Ожидание запроса...');
            fs::delete('log');
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " install $apk" . $GLOBALS['space'], true);
            $this->getlistapk();
            $form->hidePreloader();
            UXDialog::show("Успешно установился => $apk" , 'INFORMATION');
        }
    }
    
    /**
     * Отправить файл на диск
     */
    public function sendFileToDisk ($path) {
        $this->fileChooser->execute();
        $file = $this->fileChooser->file;
        if ($file == null) {
            return;
        }
        if (uiConfirm("Вы точно хотите это отправить ?")) {
            $form = app()->getForm(disk);
            $form->showPreloader('Ожидание запроса...');
            fs::delete('log');
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " push $file $path" . $GLOBALS['space'], true);
            file_put_contents('log' , trim(Stream::getContents('log')));
            if (trim(str::sub(file_get_contents('log') , 0 , 24)) == 'adb: error: cannot stat') {
                UXDialog::showAndWait('Ошибка не удается найти файл!' , 'ERROR');
            } else {
                UXDialog::show("Успешно отправился файл!" , 'INFORMATION');
                $this->getdir();
            }
            $form->hidePreloader();
        }
    }
    
    /**
     * удаление файла на диск
     */
    public function deleteFile ($path , $confirm) {
        if ($confirm == false) {
            $form = app()->getForm(disk);
            $form->showPreloader('Ожидание запроса...');
            fs::delete('log');
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell rm -Rf $path" . $GLOBALS['space'], true);
            file_put_contents('log' , trim(Stream::getContents('log')));
            $this->getdir();
            $form->hidePreloader();
            UXDialog::show("Успешно удалился файл!" , 'INFORMATION');
            return;
        }
        if (uiConfirm("Вы точно хотите это удалить ?")) {
            $form = app()->getForm(disk);
            $form->showPreloader('Ожидание запроса...');
            fs::delete('log');
            execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell rm -Rf $path" . $GLOBALS['space'] , true);
            file_put_contents('log' , trim(Stream::getContents('log')));
            $this->getdir();
            $form->hidePreloader();
            UXDialog::show("Успешно удалился файл!" , 'INFORMATION');
        }
    }
    
    /**
     * Открыть изоброжение в проводнике
     */
    public function selectedimg ($file) {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        $extension = str::split($file , '.');
        if ($extension[1] != 'png' && $extension[1] != 'jpg' && $extension[1] != 'jpeg') {
            $form->hidePreloader();
            return ;
        }
        fs::delete('log');
        $path = 'disk';
        if (fs::exists($path . fs::separator() . 'tmp')) {
            fs::delete($path . fs::separator() . 'tmp');
        }
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " pull $file $path" . $GLOBALS['space'] , true);
        $files = fs::scan($path , ['extensions' => ['png' , 'jpg' , 'jpeg'] , 'excludeDirs' => false]);
        foreach ($files as $value) {
            $extension = str::split($value , '.');
            rename($value , "$path/tmp");
            $form->image->image = new UXImage("$path/tmp");
        }
        $form->hidePreloader();
    }
    
    /**
     * Скачивание всех картинок
     */
    public function downloadAllImg () {
        $form = app()->getForm(disk);
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        $path = 'disk/img';
        if ($form->listfile->items->isEmpty()) {
            UXDialog::showAndWait('Отправить ничего невозможно = (' , 'ERROR');
            $form->hidePreloader();
            return ;
        }
        foreach ($form->listfile->items->toArray() as $value) {
            if (!str::endsWith($form->path->text , '/')) {
                $input = $form->path->text . '/' . $value;
            } else {
                $input = $form->path->text . $value;
            }        
            $extension = str::split($value , '.');
            if ($extension[1] != 'png' && $extension[1] != 'jpg' && $extension[1] != 'jpeg') {
               
            } else {
                execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " pull $input $path/$value" . $GLOBALS['space'] , true);
            }
        }
        $form->hidePreloader();
        UXDialog::showAndWait('Успешно :)' , 'INFORMATION');
    }
    
    /**
     * Обновить apk list
     */
    public function getlistapk () {
        $form = app()->getForm(apkmanager);
        $form->listapk->items->clear();
        $list = str::split($this->shell('pm list packages') , "\n");
        foreach ($list as $value) {
            $i++;
            $text = str::split(trim($value) , ':');
            $form->listapk->items->add($text[1]);
        }
        $form->countapklist->text = $i;
    }
     
    /**
     * Открыть url адрес в браузере
     */
    public function browser ($url) {
        $this->shell("am start -d $url -a android.intent.action.VIEW");
        UXDialog::showAndWait('Успешно открылось' , 'INFORMATION');
    }   
     
    /**
     * Исполнить shell и возвращает результат
     */
    public function shell ($cmd) {  
        $form = app()->getForm(MainForm);
        $ip = $form->Devices->selectedItem;
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell $cmd" . $GLOBALS['space'], true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        if (file_get_contents('log') == "error: no such device '$ip:5555'") {
            
        }
        $form->hidePreloader();
        return file_get_contents('log');
    }
    
    /**
     * создать папку
     */
    public function mkdir ($namedir) {
        $form = app()->getForm(disk);
        if (!str::endsWith($form->path->text , '/')) {
            $path = $form->path->text . '/' . $namedir;
        } else {
            $path = $form->path->text . $namedir;
        }
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell mkdir $path" . $GLOBALS['space'] , true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        $this->getdir();
        $form->hidePreloader();
        UXDialog::showAndWait('Папка успешно создана!' , 'INFORMATION');
    }
    
    /**
     * Вызвать командную оболочку shell
     */
    public function shell_show () {  
        $form = app()->getForm(MainForm);
        $ip = $form->Devices->selectedItem;
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell" . $GLOBALS['space'] , true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        if (file_get_contents('log') == 'error: no devices/emulators found') {
            UXDialog::showAndWait('Запустить shell не получится = (' , 'ERROR');
        } else if (file_get_contents('log') == 'error: device offline') {
            UXDialog::showAndWait('Устройства в оффлайне!' , 'ERROR');
        }
        $form->hidePreloader();
    }
    
    /**
     * Открыть файл 
     */
    public function openfile ($path, $listfile) {
        if (!str::endsWith($path , '/')) {
            $this->selectedimg($path . '/' . $listfile);
        } else {
            $this->selectedimg($path . $listfile);
        }
    }
     
    /**
     * Действие кнопки
     */
    public function keyevent ($key) {  
        $form = app()->getForm(MainForm);
        $ip = $form->Devices->selectedItem;
        $form->showPreloader('Ожидание запроса...');
        fs::delete('log');
        execute($GLOBALS['cmd'] . 'android' . fs::separator() . 'platform-tools' . fs::separator() . $GLOBALS['file_execute'] . " shell input keyevent $key" . $GLOBALS['space'], true);
        file_put_contents('log' , trim(Stream::getContents('log')));
        $form->hidePreloader();
    }
    
}
