<?php

require_once __DIR__ . '\RomanNumeralGenerator.php';

class Converter implements iRomanNumeralGenerator
{
    //Roman symbols and their int values
    private $_romanValues = array(
                    'M'  => 1000,
                    'CM' => 900,
                    'D'  => 500,
                    'CD' => 400,
                    'C'  => 100,
                    'XC' => 90,
                    'L'  => 50,
                    'XL' => 40,
                    'X'  => 10,
                    'IX' => 9,
                    'V'  => 5,
                    'IV' => 4,
                    'I'  => 1);
    
    public function __construct() {}
    
    /**
    * Generate the roman number corresponding to the integer number received
    * @param integer $intNumber number to be converted to roman
    * @return string | boolean 
    */
    public function generate($intNumber)
    {
        if (! filter_var($intNumber, FILTER_VALIDATE_INT, array('options'=>array('min_range'=>1, 'max_range'=>3999)))) {
            return false;
        }
        $sRoman = '';

        foreach ($this->_romanValues as $key => $value) {
            //get how many times the value of the current roman key is contained in the integer
            $n = intval($intNumber / $value);

            if($n > 0) {
                //concanate the roman key the times needed
                $sRoman .= str_repeat($key, $n);

                //substract from the int number the value corresponding to the roman symbols added to the result
                $intNumber -= $value * $n;
            }
        }
        
        return $sRoman;
    }
    
    /**
    * Generate the integer number corresponding to roman number received
    * @param string $sRoman string representing the roman number to be converted
    * @return integer | boolean
    */
    public function parse($sRoman)
    {
        //Check if param is a valid roman number
        if(empty($_GET['input']) || ! preg_match('/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/', $sRoman) > 0 ) {
            return  false;
        }

        $result = 0;
        //iterate through roman number from left to right
        for ($i = 0, $length = strlen($sRoman); $i < $length; $i++) {
            //get value of current roman symbol
            $currentValue = $this->_romanValues[$sRoman[$i]];
            //get value of next char. Null if there isn't any next char 
            $nextValue = !isset($sRoman[$i + 1]) ? null : $this->_romanValues[$sRoman[$i + 1]];
            //subtract current value from result if next value is greater than current one, otherwise add it
            $result += (!is_null($nextValue) && $nextValue > $currentValue) ? -$currentValue : $currentValue;
        }
        return $result;
    }
}