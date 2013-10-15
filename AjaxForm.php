<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
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

