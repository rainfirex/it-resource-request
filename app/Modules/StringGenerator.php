<?php

namespace App\Modules;


class StringGenerator
{
    /**
     *
     * @param string $username
     * @return string
     */
    public static function login(string $username) : string{
        preg_match('/(\w+)\s?(\w+)\s?(\w*)/', self::translate(mb_strtolower($username)), $matches);

        $lastName = $firstName = $patronymic = null;

        if (isset($matches[1]))
            $lastName  = $matches[1];

        if (isset($matches[2])){
            $firstName = mb_substr($matches[2],0,1);
        }

        if (isset($matches[3])){
            $patronymic = mb_substr($matches[3],0,1);
        }

        return sprintf('%s_%s%s', $lastName, $firstName, $patronymic);
    }

    /**
     *
     * @param string $username
     * @param string $domenname
     * @return string
     */
    public static function email(string $username, string $domenname) : string{
        preg_match('/(\w+)\s?(\w+)\s?(\w*)/', self::translate(mb_strtolower($username)), $matches);

        $lastName = $firstName = $patronymic = null;

        if (isset($matches[1]))
            $lastName  = $matches[1];

        if (isset($matches[2])){
            $firstName = mb_substr($matches[2],0,1);
        }

        if (isset($matches[3])){
            $patronymic = mb_substr($matches[3],0,1);
        }

        return sprintf('%s-%s%s@%s',$lastName, $firstName, $patronymic, $domenname);
    }

    /**
     * Транслит в англ.
     * @param string $filename
     * @return string
     */
    public static function translate(string $text)
    {
        $alphas = [
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        ];
        return strtr($text, $alphas);
    }

    /**
     * Короткое имя пользователя
     * @param string $username
     * @return string
     */
    public static function shortUsername(string $username): string {
        $arrName = explode(' ', trim($username));
        $shortName = $arrName[0];
        if(isset($arrName[1]))
            $shortName .= ' '. lcfirst(mb_substr($arrName[1],0,1)).'.';
        if(isset($arrName[2]))
            $shortName .= ' '.lcfirst(mb_substr($arrName[2],0,1)).'.';
        return $shortName;
    }
}
