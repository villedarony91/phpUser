<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit48e841a7d53186a6322611431f9e9a88
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PhpAmqpLib' => 
            array (
                0 => __DIR__ . '/..' . '/videlalvaro/php-amqplib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit48e841a7d53186a6322611431f9e9a88::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
