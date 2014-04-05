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


	public function generate()
	{
		if (\Environment::get('isAjaxRequest')) {
    		$this->strTemplate = 'ajaxform_inline';

    		$objResponse = new HtmlResponse(parent::generate());
			$objResponse->send();
		}

		return parent::generate();
	}


	protected function jumpToOrReload($intId, $strParams=null, $strForceLang=null)
	{
		$this->Template = new \FrontendTemplate('ajaxform_confirm');
		$this->Template->message = $this->objParent->text;

		if (\Environment::get('isAjaxRequest'))
		{
		    $objResponse = new HtmlResponse($this->objParent->text ? $this->Template->parse() : 'true');
			$objResponse->send();
		}

		if ($this->objParent->text)
		{
			return;
		}
	}
}
