<?php

namespace CCMS\Email\Components\TemplateForm;

interface ITemplateForm
{
    /**
     * @param null|int $id
     * @return TemplateForm
     */
    function create($id = null);
}