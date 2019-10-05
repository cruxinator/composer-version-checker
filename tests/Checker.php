<?php

namespace Cruxinator\ComposerVersionChecker\Tests;

use PHPUnit\Framework\TestCase;
use Cruxinator\ComposerVersionChecker\Checker as MainCheck;

class Checker extends TestCase
{
    /**
     * This is purposely not implemented to demonstrated the output in VPU.
     *
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
}
