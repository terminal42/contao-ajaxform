<?php

/**
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2009-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/aschempp/contao-ajaxform
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'AjaxForm'          => 'system/modules/ajaxform/AjaxForm.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'ajaxform_confirm'  => 'system/modules/ajaxform/templates',
    'ajaxform_inline'   => 'system/modules/ajaxform/templates',
    'ajaxform'          => 'system/modules/ajaxform/templates',
));
