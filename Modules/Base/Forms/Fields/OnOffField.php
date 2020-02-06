<?php

namespace Modules\Base\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class OnOffField extends FormField
{

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'Base::forms.fields.on-off';
    }
}
