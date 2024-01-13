<?php

namespace App\Service;

class SkuGenerator
{
    public function generator(): string
    {
        $today = date('Ymd');
        $randomNumber= mt_rand(1,999);
        return $today . $randomNumber;
    }
}