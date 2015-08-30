<?php

interface iRomanNumeralGenerator {
    
    public function generate($intNumber);
    
    public function parse($sRoman);
}