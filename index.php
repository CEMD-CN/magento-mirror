<?php
//入口文件
if (version_compare(phpversion(), '5.3.0', '<')===true) {
    echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;">
<div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
<h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">
Whoops, it looks like you have an invalid PHP version.</h3></div><p>Magento supports PHP 5.3.0 or newer.
<a href="http://www.magentocommerce.com/install" target="">Find out</a> how to install</a>
 Magento using PHP-CGI as a work-around.</p></div>';
    exit;
}
error_reporting(E_ALL | E_STRICT);//使用此函数来指定抛出error的类型
define('MAGENTO_ROOT', getcwd()); //定义根目录
$compilerConfig = MAGENTO_ROOT . '/includes/config.php';
if (file_exists($compilerConfig)) { //判断是否存在config文件，这个文件能改变什么？
    include $compilerConfig;
}
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';//核心文件，总管大人
$maintenanceFile = 'maintenance.flag';
if (!file_exists($mageFilename)) {//如果核心文件不存在的话，跳转到downloader目录去下载
    if (is_dir('downloader')) {
        header("Location: downloader");
    } else {
        echo $mageFilename." was not found";
    }
    exit;
}
if (file_exists($maintenanceFile)) {//如果存在maintenance文件的话，输出503的内容，就直接退出了
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}
require_once $mageFilename;
#Varien_Profiler::enable();
if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}
#ini_set('display_errors', 1);//控制错误信息是否输出到页面
umask(0);
/* Store or website code */
$mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';
/* Run store or run website */
$mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';
Mage::run($mageRunCode, $mageRunType);
