<?php

namespace Takielias\TablarKit\Traits;

use Takielias\TablarKit\Builder\AbstractForm;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait HandlesTablarForms
{
    /**
     * Create form instance from class name
     */
    protected function makeForm(string $formClass): AbstractForm
    {
        if (!class_exists($formClass)) {
            throw new \InvalidArgumentException("Form class {$formClass} does not exist");
        }

        if (!is_subclass_of($formClass, AbstractForm::class)) {
            throw new \InvalidArgumentException("Form class {$formClass} must extend AbstractForm");
        }

        return new $formClass();
    }

    /**
     * Validate request using form rules
     */
    protected function validateForm(Request $request, AbstractForm $form): array
    {
        return $request->validate(
            $form->getValidationRules(),
            $form->getValidationMessages()
        );
    }

    /**
     * Handle form submission with validation
     */
    protected function handleFormSubmission(Request $request, string|AbstractForm $form, callable $callback)
    {
        if ($form instanceof AbstractForm) {
            $formInstance = $form;
        } else {
            $formInstance = $this->makeForm($form);
        }

        try {
            $validated = $this->validateForm($request, $formInstance);
            return $callback($validated, $formInstance);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
