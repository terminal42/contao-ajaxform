<?php

/*
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-ajaxform
 */

/**
 * Register the classes.
 */
ClassLoader::addClasses(
[
    'AjaxForm' => 'system/modules/ajaxform/AjaxForm.php',
]);

/*
 * Register the templates
 */
TemplateLoader::addFiles(
[
    'ajaxform_confirm' => 'system/modules/ajaxform/templates',
    'ajaxform_inline' => 'system/modules/ajaxform/templates',
    'ajaxform' => 'system/modules/ajaxform/templates',
]);
