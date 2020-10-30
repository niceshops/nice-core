<?php


namespace Niceshops\Core\Normalizer;



class Normalizer
{
    public function normalize($value)
    {
        $result = preg_replace(['#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'], ['_' . '\1', '_' . '\1'], $value);
        if (is_array($value) && is_array($result)) {
            return array_map(function($value){
                return strtolower(trim($value));
            }, $result);
        }
        return strtolower(trim($result));
    }
}
