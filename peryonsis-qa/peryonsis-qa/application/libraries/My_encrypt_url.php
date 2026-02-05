<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class My_encrypt_url extends CI_Encrypt
{
    function encode($string, $key="", $url_safe=TRUE)
    {
        $ret = parent::encode($string, $key);

        if ($url_safe)
        {
            $ret = strtr(
                    $ret,
                    array(
                        '+' => '.',
                        '=' => '-',
                        '/' => '~'
                    )
                );
        }

        return $ret;
    }

    function decode($string, $key="")
    {
        $string = strtr(
                $string,
                array(
                    '.' => '+',
                    '-' => '=',
                    '~' => '/'
                )
        );

        return parent::decode($string, $key);
    }
}