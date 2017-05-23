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
 * Palettes.
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['ajaxform'] = '{title_legend},name,headline,type;{include_legend},form;{text_legend},text;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['text'] =
[
    'label' => &$GLOBALS['TL_LANG']['tl_module']['text'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => ['mandatory' => true, 'rte' => 'tinyMCE', 'helpwizard' => true],
    'explanation' => 'insertTags',
    'sql' => 'mediumtext NULL',
];
