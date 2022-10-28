<?php

/*
 * ajaxform extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2008-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-ajaxform
 */

use Contao\CoreBundle\Exception\ResponseException;
use Contao\Environment;
use Contao\Form;
use Contao\FrontendTemplate;
use Contao\InsertTags;
use Contao\System;
use Symfony\Component\HttpFoundation\Response;

class AjaxForm extends Form
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

        // Pass parent content or module model to the ajax form templates
        $this->parentModel = $this->objParent;

        if (Environment::get('isAjaxRequest') && Environment::get('httpContaoAjaxForm') === $formId) {
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
        static::$objStatic->Template = new FrontendTemplate('ajaxform_confirm');
        static::$objStatic->Template->parentModel = static::$objStatic->objParent;
        static::$objStatic->Template->message = static::$objStatic->objParent->text;

        if (Environment::get('isAjaxRequest')) {
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

        // Use the complete URL if the action is not available
        if (!$this->Template->action) {
            $this->Template->action = Environment::get('uri');
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
        $container = System::getContainer();
        $content = $container->has('contao.insert_tag_parser') ? $container->get('contao.insert_tag_parser')->replaceInline($content) : (new InsertTags())->replace($content);

        throw new ResponseException(new Response($content));
    }
}
