<?php


namespace App\Controllers;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('asset_url', [$this, 'getAssetUrl'])
        ];
    }

    public function getAssetUrl($path){
        if(isset($_SERVER['HTTPS']))
        {
            $protocol = 'https';
        }
        else
        {
            $protocol = 'http';
        }
        return $protocol.'://'.$_SERVER['HTTP_HOST'].'/'.$path;
    }

}