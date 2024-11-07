<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class Discount implements DataAwareRule, ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pricing = DB::table('service')->where('service.uniqueid','=',$this->data['services'])->select('pricing')->first();
        $dis_amount = (int) $value;
        $orig_price = (int) $pricing->pricing;
        if($dis_amount > $orig_price) {
            $fail('Discount must be less than or equal price');
        };
    }
}
