<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcdb511163d7999c395550cb0a5d035cc
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Theme\\FlexHome\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Theme\\FlexHome\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcdb511163d7999c395550cb0a5d035cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcdb511163d7999c395550cb0a5d035cc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
