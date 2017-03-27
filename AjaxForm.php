<?php

/**
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2009-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/aschempp/contao-ajaxform
 */

use Haste\Http\Response\HtmlResponse;


class AjaxForm extends \Form
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ajaxform';

	protected static $objStatic;


	public function generate()
	{
	    static::$objStatic = $this;
		$formId = ($this->formID != '') ? 'auto_'.$this->formID : 'auto_form_'.$this->id;

		if (\Environment::get('isAjaxRequest') && \Input::post('FORM_SUBMIT') == $formId) {
    		$this->strTemplate = 'ajaxform_inline';

    		$objResponse = new HtmlResponse(parent::generate());
			$objResponse->send();
		}

        // Replace the default Contao 4 template
        if ($this->customTpl === 'form_wrapper') {
            $this->customTpl = $this->strTemplate;
        }

		return parent::generate();
	}


	protected function compile()
	{
    	parent::compile();

        // Check for javascript framework
        if (TL_MODE == 'FE') {

            /** @type PageModel $objPage */
    		global $objPage;

    		$this->Template->jquery = false;
    		$this->Template->mootools = false;

            if ($objPage->getRelated('layout')->addJQuery) {
                $this->Template->jquery = true;
            } elseif ($objPage->getRelated('layout')->addMooTools) {
                $this->Template->mootools = true;
            }
        }
	}


	protected function jumpToOrReload($intId, $strParams=null, $strForceLang=null)
	{
	    $this->reload();
	}

	public static function reload()
	{
		static::$objStatic->Template = new \FrontendTemplate('ajaxform_confirm');
		static::$objStatic->Template->message = static::$objStatic->objParent->text;

		if (\Environment::get('isAjaxRequest')) {
		    $objResponse = new HtmlResponse(static::$objStatic->objParent->text ? static::$objStatic->Template->parse() : 'true');
			$objResponse->send();
		}

		if (static::$objStatic->objParent->text) {
			return;
		}
	}
}
