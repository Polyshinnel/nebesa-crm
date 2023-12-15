<?php


namespace App\Utils;


class ToolClass
{
    public function getPostRequest(string $url, array $header, array $data = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function getGetRequest(string $url, array $header) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function reformatDate(string $date, string $lang = 'ru'): string
    {
        if ($lang == 'ru') {
            $dateArr = explode('-', $date);
            return $dateArr[2].'.'.$dateArr[1].'.'.$dateArr[0];
        } else {
            $dateArr = explode('.',$date);
            return $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0];
        }

    }

    public function normalizeNum($num) {
        if(!empty($num)) {
            $num = explode('.',$num);
            $sum = $num[0];
            return mb_substr($sum, 0, -2);
        }
        return 0;
    }
}