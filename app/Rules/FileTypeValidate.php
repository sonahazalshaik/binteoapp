<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileTypeValidate implements Rule
{
    /**
     * File extensions
     *
     * @var array
     */
    protected $extensions;

    /**
     * Create a new rule instance.
     *
     * @param array $extensions
     * @return void
     */
    public function __construct($extensions = [])
    {
        $this->extensions = $extensions;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) {
            return true;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        return in_array($extension, $this->extensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a file of type: ' . implode(', ', $this->extensions) . '.';
    }
}
