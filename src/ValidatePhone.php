<?php
namespace Emleons\Games;

/*****
accepted formats
0656423482
+255656423482
255656423482
 *****/
class ValidatePhone
{
    private function startsWithPlus255($phoneNumber)
    {
        return substr($phoneNumber, 0, 4) === '+255';
    }

    private function startsWith255($phoneNumber)
    {
        return substr($phoneNumber, 0, 3) === '255';
    }

    private function startsWith0($phoneNumber)
    {
        return substr($phoneNumber, 0, 1) === '0';
    }

    private function checkPhoneNumber($phoneNumber)
    {
        $startsWithPlus255 = $this->startsWithPlus255($phoneNumber);
        $startsWith255     = $this->startsWith255($phoneNumber);
        $startsWith0       = $this->startsWith0($phoneNumber);

        return $startsWithPlus255 || $startsWith255 || $startsWith0;
    }

    private function removeSpacesAndDashes($phoneNumber)
    {
        return str_replace([' ', '-'], '', $phoneNumber);
    }

    public function validate($phoneNumber)
    {
        $phoneNumber = $this->removeSpacesAndDashes($phoneNumber);

        if (!$this->checkPhoneNumber($phoneNumber)) {
            return false;
        }

        if (!preg_match('/^(\+255|255|0)[1-9][0-9]{7,8}$/', $phoneNumber)) {
            return false;
        }

        return true;
    }

    public function remove_tz_prefix($phone_number)
    {
        $phone_number = preg_replace('/[\s-]+/', '', $phone_number); // Remove spaces and dashes from the phone number
        $prefixes     = array('+255', '255', '0');
        foreach ($prefixes as $prefix) {
            if (strpos($phone_number, $prefix) === 0) { // Check if the phone number starts with a prefix
                return substr($phone_number, strlen($prefix)); // Remove the prefix and return the remaining phone number
            }
        }
        echo $phone_number . '&nbsp' . "is an invalid phone number"; // If no valid prefix is found, display an error message
    }
}