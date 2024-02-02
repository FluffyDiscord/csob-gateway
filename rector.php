<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
                   ->withPaths([
                       __DIR__ . '/src',
                       __DIR__ . '/tests',
                   ])
                   ->withSets([
                       \Rector\Set\ValueObject\DowngradeLevelSetList::DOWN_TO_PHP_71,
                   ])
                   ->withPhpVersion(\Rector\ValueObject\PhpVersion::PHP_71)
;
