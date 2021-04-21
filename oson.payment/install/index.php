<?php
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class oson_payment extends CModule
{
    var $MODULE_ID = 'oson.payment';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_MODE_EXEC = 'bitrix';

    // пути
    var $PATH;
    var $PATH_INSTALL;

    // пути в CMS
    var $BXPATH;
    public function __construct()
    {
        // информация о модуле и разработчике
        $this->MODULE_NAME = Loc::getMessage('NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('PARTNER_URI');
        // версия
        $arModuleVersion = [
            'VERSION' => '',
            'VERSION_DATE' => ''
        ];
        include('version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        // пути
        global $DBType;
        $this->PATH = $_SERVER['DOCUMENT_ROOT'] . "/$this->MODULE_MODE_EXEC/modules/$this->MODULE_ID";
        $this->PATH_INSTALL = "$this->PATH/install";
        // пути в CMS битрикс
        $this->BXPATH = $_SERVER['DOCUMENT_ROOT'] . "/$this->MODULE_MODE_EXEC";
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            "{$this->PATH}/install/public/sale_payment",
            "{$this->BXPATH}/php_interface/include/sale_payment",
            true,
            true
        );

        CopyDirFiles(
            "{$this->PATH}/install/public/site",
            $_SERVER["DOCUMENT_ROOT"],
            true,
            true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("{$this->BXPATH}/php_interface/include/sale_payment/{$this->MODULE_ID}");
        return true;
    }

    public function DoInstall()
    {
        $this->InstallFiles();
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $this->UnInstallFiles();
        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}