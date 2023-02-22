<?php

namespace Neo4j\Neo4jBundle\Tests\Functional;

use Neo4j\Neo4jBundle\Tests\Functional\app\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class BaseTestCase extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return AppKernel::class;
    }
}
