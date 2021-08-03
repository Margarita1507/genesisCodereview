<?php


namespace App\Service;


class BTCService
{
    //BTC rate are pulled from another site
    static function currentBtcRate(): int
    {
        $content = file_get_contents('https://alpari.com/ru/converter/btc-uah/');
        preg_match('/1 BTC = (?<btcRate>[\d]*)/', $content, $matches);
        return $matches['btcRate'];
    }
}