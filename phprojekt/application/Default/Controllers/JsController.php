<?php
/**
 * JavaScript Controller.
 * The controller will return all the js files for the modules.
 *
 * This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License version 3 as published by the Free Software Foundation
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * @category   PHProjekt
 * @package    Application
 * @subpackage Default
 * @copyright  Copyright (c) 2010 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL v3 (See LICENSE file)
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 * @version    Release: @package_version@
 * @author     Gustavo Solt <solt@mayflower.de>
 */

/**
 * JavaScript Controller.
 * The controller will return all the js files for the modules.
 *
 * @category   PHProjekt
 * @package    Application
 * @subpackage Default
 * @copyright  Copyright (c) 2010 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL v3 (See LICENSE file)
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 * @version    Release: @package_version@
 * @author     Gustavo Solt <solt@mayflower.de>
 */
class JsController extends IndexController
{
    /**
     * Array with all the modules found.
     *
     * @var array
     */
    private $_modules = array();

    /**
     * Array with all the templates by module.
     *
     * @var array
     */
    private $_templates = array();

    /**
     * Collect all the js files and return it as one.
     *
     * @return void
     */
    public function indexAction()
    {
        echo 'dojo.registerModulePath("phpr", "../../../application/Default/Views/dojo/scripts/system");';
        echo 'dojo.registerModulePath("phpr.Default", "../../../application/Default/Views/dojo/scripts");';
        echo 'dojo.registerModulePath("phpr.Core", "../../../application/Core/Views/dojo/scripts");';

        // System files, must be parsed in this order
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/phpr.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/Component.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/form.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/grid.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/Store.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/Date.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/Url.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/Tree.js');
        echo file_get_contents(PHPR_CORE_PATH . '/Default/Views/dojo/scripts/system/FrontendMessage.js');

        // Default Folder
        $scripts = scandir(PHPR_CORE_PATH . '/Default/Views/dojo/scripts');
        echo $this->_getModuleScripts($scripts, 'Default');

        // Core Folder
        $scripts = scandir(PHPR_CORE_PATH . '/Core/Views/dojo/scripts');
        echo $this->_getModuleScripts($scripts, 'Core');

        // Load all modules and make and array of it
        $files = scandir(PHPR_CORE_PATH);
        foreach ($files as $file) {
            if ($file != '.'  &&
                $file != '..' &&
                $file != '.svn' &&
                $file != 'Default') {
                if (is_dir(PHPR_CORE_PATH . '/' . $file . '/Views/dojo/scripts/')) {
                    $scripts = scandir(PHPR_CORE_PATH . '/' . $file . '/Views/dojo/scripts/');
                } else {
                    $scripts = array();
                }
                $this->_modules[]         = $file;
                $this->_subModules[$file] = array();
                if ($file != 'Core') {
                    echo 'dojo.registerModulePath' . '("phpr.' . $file . '", "../../../application/' . $file
                        . '/Views/dojo/scripts");';
                    echo $this->_getModuleScripts($scripts, $file);
                    if (is_dir(PHPR_CORE_PATH . '/' . $file . '/SubModules/')) {
                        $subFiles = scandir(PHPR_CORE_PATH . '/' . $file . '/SubModules/');
                        foreach ($subFiles as $subFile) {
                            if ($subFile != '.'  &&
                                $subFile != '..' &&
                                $subFile != '.svn') {
                                if (is_dir(PHPR_CORE_PATH . '/' . $file . '/SubModules/' . $subFile
                                    . '/Views/dojo/scripts/')) {
                                    $subScripts = scandir(PHPR_CORE_PATH . '/' . $file . '/SubModules/' . $subFile
                                        . '/Views/dojo/scripts/');
                                } else {
                                    $subScripts = array();
                                }
                                $this->_subModules[$file][] = "'" . $subFile . "'";
                                echo 'dojo.registerModulePath' . '("phpr.' . $subFile . '", "../../../application/' . $file
                                    . '/SubModules/' . $subFile . '/Views/dojo/scripts");';
                                echo $this->_getModuleScripts($subScripts, $file . '/SubModules/' . $subFile);
                            }
                        }
                    }
                } else {
                    echo $this->_getCoreModuleScripts($scripts);
                    if (is_dir(PHPR_CORE_PATH . '/' . $file . '/SubModules/')) {
                        $subModulesFiles = scandir(PHPR_CORE_PATH . '/' . $file . '/SubModules/');
                        foreach ($subModulesFiles as $subModule) {
                            if ($subModule != '.'  &&
                                $subModule != '..' &&
                                $subModule != '.svn') {

                                $subFiles = scandir(PHPR_CORE_PATH . '/' . $file . '/SubModules/' . $subModule);
                                foreach ($subFiles as $subFile) {
                                    if ($subFile != '.'  &&
                                        $subFile != '..' &&
                                        $subFile != '.svn') {
                                        if (is_dir(PHPR_CORE_PATH . '/' . $file . '/SubModules/' . $subModule . '/' . $subFile
                                            . '/Views/dojo/scripts/')) {
                                            $subScripts = scandir(PHPR_CORE_PATH . '/' . $file . '/SubModules/'
                                                . $subModule . '/' . $subFile . '/Views/dojo/scripts/');
                                        } else {
                                            $subScripts = array();
                                        }
                                        $this->_subModules[$subModule][] = "'" . $subFile . "'";
                                        echo 'dojo.registerModulePath' . '("phpr.' . $subFile . '", "../../../application/' . $file
                                            . '/SubModules/' . $subModule . $subFile . '/Views/dojo/scripts");';
                                        echo $this->_getModuleScripts($subScripts, $file . '/SubModules/' . $subModule
                                            . '/' . $subFile);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // Preload all the templates and save them into __phpr_templateCache
        echo 'var __phpr_templateCache = {};';

        foreach ($this->_templates as $templateData) {
            $content = str_replace("'", "\'", $templateData['contents']);
            $content = str_replace("<", "<' + '", $content);
            echo '
                __phpr_templateCache["phpr.' . $templateData['module'] . '.template.' . $templateData['name']
                . '"] = \'' . $content . '\';';
        }

        echo 'dojo.provide("phpr.Main");';

        echo '
        dojo.declare("phpr.Main", null, {
            constructor:function(/*String*/webpath, /*String*/currentModule, /*Int*/rootProjectId,/*String*/language) {
                phpr.module           = currentModule;
                phpr.submodule        = null;
                phpr.webpath          = webpath;
                phpr.rootProjectId    = rootProjectId;
                phpr.currentProjectId = rootProjectId ;
                phpr.currentUserId    = 0;
                phpr.language         = language;
                phpr.config           = new Array();
                phpr.serverFeedback   = new phpr.ServerFeedback();
                phpr.Date             = new phpr.Date();
                phpr.loading          = new phpr.loading();
                phpr.DataStore        = new phpr.DataStore();
                phpr.InitialScreen    = new phpr.InitialScreen();
                phpr.BreadCrumb       = new phpr.BreadCrumb();
                phpr.frontendMessage  = new phpr.FrontendMessage();
                phpr.Tree             = new phpr.Tree();
                phpr.regExpForFilter  = new phpr.regExpForFilter();
                phpr.globalModuleUrl  = webpath + "index.php/Core/module/jsonGetGlobalModules";
        ';

        foreach ($this->_modules as $module) {
            if (isset($this->_subModules[$module]) && !empty($this->_subModules[$module])) {
                $subModules = join(",", $this->_subModules[$module]);
            } else {
                $subModules = '';
            }
            echo '
                this.' . $module . ' = new phpr.' . $module . '.Main([' . $subModules . ']);
            ';
        }

        // The load method of the currentModule is called
        echo '
                dojo.publish(phpr.module + ".load");
            }
        });
        ';
    }

    /**
     * Collect all the js files in the module folder, and return it as one.
     *
     * OPTIONAL request parameters:
     * <pre>
     *  - string <b>name</b> Name of the module to consult.
     * </pre>
     *
     * @return void
     */
    public function moduleAction()
    {
        $module = Cleaner::sanitize('alnum', $this->getRequest()->getParam('name', null));
        $module = ucfirst(str_replace(" ", "", $module));

        // Load the module
        if (is_dir(PHPR_CORE_PATH . '/' . $module . '/Views/dojo/scripts/')) {
            $scripts = scandir(PHPR_CORE_PATH . '/' . $module . '/Views/dojo/scripts/');
        } else {
            $scripts = array();
        }

        echo 'dojo.registerModulePath'
            . '("phpr.' . $module . '", "../../../application/' . $module . '/Views/dojo/scripts");';
        echo $this->_getModuleScripts($scripts, $module);

        // Preload the templates and save them into __phpr_templateCache
        foreach ($this->_templates as $templateData) {
            $content = str_replace("'", "\\" . "'", $templateData['contents']);
            $content = str_replace("<", "<' + '", $content);
            echo '
                __phpr_templateCache["phpr.' . $templateData['module'] . '.template.' . $templateData['name']
                . '"] = \'' . $content . '\';';
        }

        echo '
            this.' . $module . ' = new phpr.' . $module . '.Main();
        ';
    }

    /**
     * Get all the Modules scripts.
     * In the process also collect the templates.
     *
     * @param array  $scripts All the modules into the Module folder.
     * @param string $module  The module name.
     *
     * @return string Content of the files.
     */
    private function _getModuleScripts($scripts, $module)
    {
        $output = '';
        foreach ($scripts as $script) {
            if (substr($script, -3) == '.js') {
                $output .= file_get_contents(PHPR_CORE_PATH . '/' . $module . '/Views/dojo/scripts/' . $script);
            } else if ('template' == $script) {
                if (strstr($module, '/')) {
                    $templateModule = substr(strrchr($module, '/'), 1);
                } else {
                    $templateModule = $module;
                }
                $this->_getTemplates(PHPR_CORE_PATH . '/' . $module . '/Views/dojo/scripts/template/', $templateModule);
            }
        }

        return $output;
    }

    /**
     * Get the Core module scripts.
     * In the process also collect the templates.
     *
     * @param array $scripts All the modules into the Core folder.
     *
     * @return string Content of the files.
     */
    private function _getCoreModuleScripts($scripts)
    {
        $output = '';
        foreach ($scripts as $script) {
            if (substr($script, -3) != '.js' && substr($script, 0, 1) != '.' && 'template' != $script) {
                // Core Modules
                $coreScripts = scandir(PHPR_CORE_PATH . '/Core/Views/dojo/scripts/' . $script);
                if (in_array('Main.js', $coreScripts)) {
                    $output .= 'dojo.registerModulePath'
                        . '("phpr.' . $script . '", "../../../application/Core/Views/dojo/scripts/' . $script . '");';
                    $this->_modules[] = $script;
                }
                foreach ($coreScripts as $coreScript) {
                    if (substr($coreScript, -3) == '.js') {
                        // Core Module files
                        $output .= file_get_contents(PHPR_CORE_PATH . '/Core/Views/dojo/scripts/'
                            . $script . '/' . $coreScript);
                    } else if ('template' == $coreScript) {
                        // Core Module templates
                        $path = PHPR_CORE_PATH . '/Core/Views/dojo/scripts/' . $script . '/' . $coreScript . '/';
                        $this->_getTemplates($path, 'Core.' . $script);
                    } else if (substr($coreScript, 0, 1) != '.') {
                        // Core Sub Modules
                        $subCoreScripts = scandir(PHPR_CORE_PATH . '/Core/Views/dojo/scripts/'
                            . $script . '/' . $coreScript);
                        if (in_array('Main.js', $subCoreScripts)) {
                            $output .= 'dojo.registerModulePath'
                                . '("phpr.' . $coreScript . '", "../../../application/Core/Views/dojo/scripts/'
                                . $script . '/' . $coreScript . '");';
                            $this->_modules[] = $coreScript;
                        }

                        foreach ($subCoreScripts as $subCoreScript) {
                            if (substr($subCoreScript, -3) == '.js') {
                                // Core Sub Modules files
                                $output .= file_get_contents(PHPR_CORE_PATH . '/Core/Views/dojo/scripts/'
                                    . $script . '/' . $coreScript . '/' . $subCoreScript);
                            } else if ('template' == $subCoreScript) {
                                // Core Sub Modules templates
                                $path = PHPR_CORE_PATH . '/Core/Views/dojo/scripts/'
                                    . $script . '/' . $coreScript . '/' . $subCoreScript . '/';
                                $this->_getTemplates($path, 'Core.' . $coreScript);
                            }
                        }
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Collect all the templates found in the $path directory.
     * Also scan the sub directories.
     *
     * @param string $path   Path for scan.
     * @param string $module Module Name.
     *
     * @return void
     */
    private function _getTemplates($path, $module)
    {
        $templates = scandir($path);
        foreach ($templates as $item) {
            if (!is_dir($path . $item)) {
                if (substr($item, -5) == '.html') {
                    // The item is a valid file
                    $fileContents = file_get_contents($path . $item);
                    $fileContents = str_replace("\n", "", $fileContents);
                    $fileContents = str_replace("\r", "", $fileContents);

                    $this->_templates[] = array('module'   => $module,
                                                'name'     => $item,
                                                'contents' => $fileContents);
                }
            } else {
                // The item is a subdirectory
                if ($item != '.svn' && $item != '.' && $item != '..') {
                    $subItemPath = $path . $item . DIRECTORY_SEPARATOR;
                    foreach (scandir($subItemPath) as $subItem) {
                        if (!is_dir($subItemPath . $subItem) && substr($subItem, -5) == '.html') {
                            // The subitem is a valid file
                            $fileContents = file_get_contents($subItemPath . $subItem);
                            $fileContents = str_replace("\n", "", $fileContents);
                            $fileContents = str_replace("\r", "", $fileContents);

                            $this->_templates[] = array('module'   => $module,
                                                        'name'     => $item . "." . $subItem,
                                                        'contents' => $fileContents);
                        }
                    }
                }
            }
        }
    }
}
