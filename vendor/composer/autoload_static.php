<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit05879499a3b712be6e75bfc81563ce4e
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Alterindonesia\\Procurex\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Alterindonesia\\Procurex\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit05879499a3b712be6e75bfc81563ce4e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit05879499a3b712be6e75bfc81563ce4e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit05879499a3b712be6e75bfc81563ce4e::$classMap;

        }, null, ClassLoader::class);
    }
}
