<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite4ed7a6e1b537af567a86959cb5d623f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite4ed7a6e1b537af567a86959cb5d623f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite4ed7a6e1b537af567a86959cb5d623f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
