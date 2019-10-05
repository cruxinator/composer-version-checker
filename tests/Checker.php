<?php

namespace Cruxinator\ComposerVersionChecker\Tests;

use PHPUnit\Framework\TestCase;
use Cruxinator\ComposerVersionChecker\Checker as MainCheck;

class Checker extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     */
    public function checkerTest($method, $package, $version, $outcome)
    {
        $this->assertEquals($outcome, MainCheck::{$method}($package, $version));
    }

    public function provider()
    {
        $phpunitVersion = \PHPUnit\Runner\Version::id();

        return [
            ['greaterThan', 'phpunit/phpunit', '0.0.0', true],
            ['greaterThanOrEqualTo', 'phpunit/phpunit', '0.0.0', true],
            ['lessThan', 'phpunit/phpunit', '100.0.0', true],
            ['lessThanOrEqualTo', 'phpunit/phpunit', '100.0.0', true],
            ['equalTo', 'phpunit/phpunit', $phpunitVersion, true],
            ['notEqualTo', 'phpunit/phpunit', '100.0.0', true],
            ['satisfies', 'phpunit/phpunit', '!= 100.0.0', true],
            ['greaterThan', 'phpunit/phpunit', $phpunitVersion, false],
            ['greaterThanOrEqualTo', 'phpunit/phpunit', $phpunitVersion, true],
            ['lessThan', 'phpunit/phpunit', $phpunitVersion, false],
            ['lessThanOrEqualTo', 'phpunit/phpunit', '100.0.0', true],
            ['notEqualTo', 'phpunit/phpunit', $phpunitVersion, false],

            ];
    }

    public function doubleProvider()
    {
        $single = $this->provider();
        $count = floor(count($single) / 2);
        $testData = [];
        for ($i = 0; $i < $count; $i++) {
            $testData[] = [
                $single[$i][0],
                $single[$i][1],
                $single[$i][2],
                $single[$i][3],
                $single[$i + 1][0],
                $single[$i + 1][1],
                $single[$i + 1][2],
                $single[$i + 1][3],
                ];
        }

        return $testData;
    }

    /**
     * @test
     * @dataProvider doubleProvider
     */
    public function checkerAlreadyBootedTest($method1, $package1, $version1, $outcome1, $method2, $package2, $version2, $outcome2)
    {
        $this->assertEquals($outcome1, MainCheck::{$method1}($package1, $version1));
        $this->assertEquals($outcome2, MainCheck::{$method2}($package2, $version2));
    }
}
