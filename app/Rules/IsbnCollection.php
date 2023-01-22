<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Intervention\Validation\Rules\Isbn;

class IsbnCollection implements InvokableRule
{
    private array $failed = [];

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
        if (! $this->passes($value, $attribute)) {
            $fail('Values in :attribute are not valid Isbn: '.implode(',', $this->failed));
        }
    }

    public function passes(mixed $value, string $attribute): bool
    {
        foreach ($value as $isbn) {
            if (! (new Isbn())->passes($attribute, $isbn)) {
                $this->failed[] = $isbn;
            }
        }

        return empty($this->failed);
    }
}
