<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

readonly class DividesBy implements InvokableRule
{
    public function __construct(private int $divisor)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        if (! $this->passes($value)) {
            $fail('Value of :attribute must be divisible by '.$this->divisor);
        }
    }

    public function passes(mixed $value): bool
    {
        return $value % $this->divisor === 0;
    }
}
