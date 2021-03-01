<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcac095ad5cf1a0ee7ce62e64c0ab6251
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

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcac095ad5cf1a0ee7ce62e64c0ab6251::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcac095ad5cf1a0ee7ce62e64c0ab6251::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcac095ad5cf1a0ee7ce62e64c0ab6251::$classMap;

        }, null, ClassLoader::class);
    }
}