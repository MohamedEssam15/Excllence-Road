<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jlorente\CreditCards\CreditCardValidator;

class CreditCard implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // public function validate(string $attribute, mixed $value, Closure $fail): void
    // {
    //     $validator = new CreditCardValidator();

    //     // Check if the card number is valid
    //     if (!$validator->isValid($value)) {
    //         $fail(__('response.creditCardError'));
    //     }
    // }

    protected $expiryDate;  // For MM-YY format
    protected $cvv;

    public function __construct($expiryDate, $cvv)
    {
        $this->expiryDate = $expiryDate;
        $this->cvv = $cvv;
    }

    /**
     * Validate the credit card number, expiry date (MM-YY), and CVV.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $validator = new CreditCardValidator();

        // Validate card number
        if (!$validator->isValid($value)) {
            $fail(__('response.creditCardError'));
            return;
        }

        // Validate expiration date (MM-YY)
        if (!$this->isExpiryDateValid($this->expiryDate)) {
            $fail(__('response.expDateError'));
            return;
        }

        // Validate CVV based on card type
        $cardType = $validator->getType($value);
        if (!$this->isCvvValid($this->cvv, $cardType)) {
            $fail(__('response.cvvError'));
        }
    }

    /**
     * Validate the expiration date in MM-YY format.
     *
     * @param  string  $expiryDate
     * @return bool
     */
    protected function isExpiryDateValid($expiryDate)
    {
        // Check if the format is correct
        if (!preg_match('/^(0[1-9]|1[0-2])-(\d{4})$/', $expiryDate, $matches)) {
            return false;
        }

        // Extract month and year from the expiration date
        $expiryMonth = $matches[1];
        $expiryYear = $matches[2];

        $currentYear = date('Y');
        $currentMonth = date('m');

        // Check if the card is expired
        if ($expiryYear < $currentYear || ($expiryYear == $currentYear && $expiryMonth < $currentMonth)) {
            return false;
        }

        return true;
    }

    /**
     * Validate the CVV based on the card type.
     *
     * @param  string  $cvv
     * @param  string  $cardType
     * @return bool
     */
    protected function isCvvValid($cvv, $cardType)
    {
        // Different card types have different CVV lengths
        $cvvLength = strlen($cvv);
        if (($cardType === 'amex' && $cvvLength === 4) || ($cardType !== 'amex' && $cvvLength === 3)) {
            return true;
        }

        return false;
    }
}


