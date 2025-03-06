<?php

namespace App\Rules;

use App\Services\RunValidatorService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRun implements ValidationRule
{
    protected $runValidator;

    public function __construct()
    {
        $this->runValidator = app(RunValidatorService::class);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->runValidator->validate($value)) {
            $fail(__('The :attribute is not a valid Chilean RUN'));
        }
    }
}