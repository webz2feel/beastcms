<?php

namespace Modules\Acl\Forms;

use Modules\Acl\Http\Requests\UpdatePasswordRequest;
use Modules\Acl\Models\User;
use Modules\Base\Forms\FormAbstract;
use Html;

class PasswordForm extends FormAbstract
{

    /**
     * @return mixed|void
     * @throws \Throwable
     */
    public function buildForm()
    {
        $this
            ->setupModel(new User)
            ->setValidatorClass(UpdatePasswordRequest::class)
            ->setFormOption('template', 'Base::forms.form-no-wrap')
            ->setFormOption('id', 'password-form')
            ->add('old_password', 'password', [
                'label'      => trans('Acl::users.current_password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->add('password', 'password', [
                'label'      => trans('Acl::users.new_password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
                'help_block' => [
                    'text' => Html::tag('span', 'Password Strength', ['class' => 'hidden'])->toHtml(),
                    'tag'  => 'div',
                    'attr' => [
                        'class' => 'pwstrength_viewport_progress',
                    ],
                ],
            ])
            ->add('password_confirmation', 'password', [
                'label'      => trans('Acl::users.confirm_new_password'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'data-counter' => 60,
                ],
                'wrapper'    => [
                    'class' => $this->formHelper->getConfig('defaults.wrapper_class') . ' col-md-6',
                ],
            ])
            ->setActionButtons(view('Acl::users.profile.actions')->render());
    }
}
