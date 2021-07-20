<?php
/*
 * Get all routes from each modules for api
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */

$component_path = app_path() . DIRECTORY_SEPARATOR . "Components";
$modules = $component_path . DIRECTORY_SEPARATOR . "CoreComponent/Modules";
if (\File::isDirectory($modules)) {
    $list = \File::directories($modules);
    foreach ($list as $module) {
        if (\File::isDirectory($module)) {
            if (\File::isFile($module . DIRECTORY_SEPARATOR . "routes.api.php")) {
                require_once $module . DIRECTORY_SEPARATOR . "routes.api.php";
            }
        }
    }
}
