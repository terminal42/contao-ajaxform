<?php

/**
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2009-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/aschempp/contao-ajaxform
 */


class AjaxForm extends Form
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ajaxform';

	/**
	 * Confirmation text
	 * @var string
	 */
	protected $strConfirmation;

	/**
	 * Ajax ID
	 * @var int
	 */
	protected $intId;

	/**
	 * Ajax action
	 * @var string
	 */
	protected $strAction;

	/**
	 * Trigger ajax mode
	 * @var bool
	 */
	protected $blnAjax = false;


	/**
	 * Initialize the object
	 * @param object
	 * @return string
	 */
	public function __construct(Database_Result $objElement)
	{
		$this->strConfirmation = $objElement->text;
		$this->intId = $objElement->id;
		$this->strAction = strpos($objElement->query, 'tl_content') === false ? 'fmd' : 'cte';

		parent::__construct($objElement);
	}


	public function generateAjax()
	{
		$this->blnAjax = true;
		$this->strTemplate = 'ajaxform_inline';

		return parent::generate();
	}


	protected function compile()
	{
		global $objPage;

		parent::compile();

		$this->Template->ajaxAction = 'ajax.php?action=' . $this->strAction . '&id=' . $this->intId . '&page=' . $objPage->id . '&language=' . $GLOBALS['TL_LANGUAGE'];
	}


	protected function jumpToOrReload($intId, $strParams=null, $strForceLang=null)
	{
		$this->Template = new FrontendTemplate('ajaxform_confirm');
		$this->Template->message = $this->strConfirmation;

		if ($this->blnAjax)
		{
			echo json_encode(array
			(
				'token'		=> REQUEST_TOKEN,
				'content'	=> strlen($this->strConfirmation) ? $this->Template->parse() : 'true',
			));

			exit;
		}

		if ($this->strConfirmation)
		{
			return;
		}
	}
}
