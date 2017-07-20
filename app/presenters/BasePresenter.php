<?php

namespace App;

use Nette;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var \WebLoader\Nette\LoaderFactory @inject */
    public $webLoader;

    /** @return CssLoader */
    protected function createComponentCss()
    {
        return $this->webLoader->createCssLoader('front');
    }

    /** @return JavaScriptLoader */
    protected function createComponentJs()
    {
        return $this->webLoader->createJavaScriptLoader('front');
    }
}
