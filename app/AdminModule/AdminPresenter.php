<?php

namespace App\AdminModule;

use App\BasePresenter;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

abstract class AdminPresenter extends BasePresenter
{
    /** @var \WebLoader\Nette\LoaderFactory @inject */
    public $webLoader;

    /** @return CssLoader */
    protected function createComponentCss()
    {
        return $this->webLoader->createCssLoader('admin');
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs()
    {
        return $this->webLoader->createJavaScriptLoader('admin');
    }
}