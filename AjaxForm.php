<?php

/*
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-ajaxform
 */

use Haste\Http\Response\HtmlResponse;

class AjaxForm extends \Form
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ajaxform';

    protected static $objStatic;

    /**
     * Generate the form.
     *
     * @return string
     */
    public function generate()
    {
        static::$objStatic = $this;
        $formId = ($this->formID !== '') ? 'auto_' . $this->formID : 'auto_form_' . $this->id;

        // This is always true for 4.4
        if (version_compare(VERSION, '4.4', '>=')) {
            $this->tableless = true;
        }

        if (\Environment::get('isAjaxRequest') && \Input::post('FORM_SUBMIT') === $formId) {
            $this->strTemplate = 'ajaxform_inline';
            $this->customTpl = 'ajaxform_inline';

            static::sendResponse(parent::generate());
        }

        // Replace the default Contao 4 template
        if ($this->customTpl === 'form_wrapper') {
            $this->customTpl = $this->strTemplate;
        }

        return parent::generate();
    }

    /**
     * Override the reload method.
     */
    public static function reload()
    {
        static::$objStatic->Template = new \FrontendTemplate('ajaxform_confirm');
        static::$objStatic->Template->message = static::$objStatic->objParent->text;

        if (\Environment::get('isAjaxRequest')) {
            static::sendResponse(static::$objStatic->objParent->text ? static::$objStatic->Template->parse() : 'true');
        }

        if (static::$objStatic->objParent->text) {
            return;
        }
    }

    /**
     * Compile.
     */
    protected function compile()
    {
        parent::compile();

        // Check for javascript framework
        if (TL_MODE === 'FE') {
            /* @type PageModel $objPage */
            global $objPage;

            $this->Template->jquery = false;
            $this->Template->mootools = false;

            if ($objPage->getRelated('layout')->addJQuery) {
                $this->Template->jquery = true;
            } elseif ($objPage->getRelated('layout')->addMooTools) {
                $this->Template->mootools = true;
            }
        }

        // Use the complete URL if the action is not available
        if (!$this->Template->action) {
            $this->Template->action = \Environment::get('uri');
        }
    }

    /**
     * Override the jumpToOrReload method to always reload.
     *
     * @param array|int $intId
     * @param null      $strParams
     * @param null      $strForceLang
     */
    protected function jumpToOrReload($intId, $strParams = null, $strForceLang = null)
    {
        $this->reload();
    }

    /**
     * Replace insert tags and send response.
     *
     * @param string $content
     */
    private static function sendResponse($content)
    {
        $insertTags = new \Contao\InsertTags();
        $content = $insertTags->replace($content, false);
        $objResponse = new HtmlResponse($content);
        $objResponse->send();
    }
}
