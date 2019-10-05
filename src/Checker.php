<?php

namespace Cruxinator\ComposerVersionChecker;

use Composer\Semver\Comparator;
use Composer\Semver\Semver ;

class Checker
{
    protected static $installedPackages = null;
    protected static $versionParser = null;
    protected static $booted = false;
    protected static function boot()
    {
        if (self::$booted) {
            return;
        }
        $composerDirectory = dirname((new \ReflectionClass('Composer\Autoload\ClassLoader'))->getFileName());
        $installedFile = $composerDirectory . DIRECTORY_SEPARATOR . 'installed.json';
        self::$installedPackages = json_decode(file_get_contents($installedFile), false);
        self::$versionParser = new \Composer\Semver\VersionParser();
    }
    protected static function getPackageNormalizedVersion($packageName)
    {
        self::boot();
        $package = array_filter(self::$installedPackages, function ($package) use ($packageName) {
            return $package->name == $packageName;
        });
        return array_pop($package)->version_normalized;
    }

    protected static function normalize($version)
    {
        return self::$versionParser->normalize($version);
    }

    /**
     * Evaluates the expression: $package->version > $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function greaterThan($package, $version)
    {
        return Comparator::greaterThan(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Evaluates the expression: $package->version >= $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function greaterThanOrEqualTo($package, $version)
    {
        return Comparator::greaterThanOrEqualTo(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Evaluates the expression: $package->version < $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function lessThan($package, $version)
    {
        return Comparator::lessThan(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Evaluates the expression: $package->version <= $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function lessThanOrEqualTo($package, $version)
    {
        return Comparator::lessThanOrEqualTo(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Evaluates the expression: $package->version == $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function equalTo($package, $version)
    {
        return Comparator::equalTo(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Evaluates the expression: $package->version != $version.
     *
     * @param string $package
     * @param string $version
     *
     * @return bool
     */
    public static function notEqualTo($package, $version)
    {
        return Comparator::notEqualTo(self::getPackageNormalizedVersion($package), self::normalize($version));
    }
    /**
     * Determine if installed package version satisfies given constraints.
     *
     * @param string $package
     * @param string $constraints
     *
     * @return bool
     */
    public static function satisfies($package, $constraints)
    {
        return Semver::satisfies(self::getPackageNormalizedVersion($package), $constraints);
    }
}
