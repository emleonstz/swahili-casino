# Tanzania Phone Number Validator

This is a simple PHP library for validating Tanzania phone numbers. It uses check if a given phone number is well formated.
by default it accepts the following fomarts:
+255956413291
255696413291
0656913291
you can modify it per your needs by editing the file from src/ValidateNamba.php
## Installation

To use this library, you need to have [Composer](https://getcomposer.org/) installed in your system. Once you have Composer installed, you can download the library by running the following command:

```
composer require emleons/tz_namba
```

## Usage

Here is an example of how to use the library to validate a phone number:

```php 
require('vendor/autoload.php');
use Emleons\TzNamba\ValidateNamba;

$test = new ValidateNamba;
$phone = "+255752123456"; // Replace with the phone number you want to validate

if($test->validate($phone)){
    echo $phone." is a valid phone".'<br>';
}else{
    echo $phone." is an invalid phone".'<br>';
}
//sometimes you may want to remove prefix from phone numbers to do it use the emove_tz_prefix() method and pass your phone that follows accepted format
$removed_prefix = $test->remove_tz_prefix($phone);
echo "Removed prefix from ".$phone." to ".$removed_prefix;
```

## Contributions

If you find a bug or have a feature request, please open an issue or submit a pull request on GitHub.

## Credits

Developed by Emleons email:emleons23@gmail.com
