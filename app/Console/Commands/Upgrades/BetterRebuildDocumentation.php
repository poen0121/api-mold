<?php

namespace App\Console\Commands\Upgrades;

use Mpociot\ApiDoc\Commands\RebuildDocumentation;
use Mpociot\ApiDoc\Tools\DocumentationConfig;
use App\Libraries\Upgrades\BetterApiDocWriter as Writer;  // Better

class BetterRebuildDocumentation extends RebuildDocumentation
{
    public function handle()
    {
        $sourceOutputPath = 'resources/docs/source';
        if (! is_dir($sourceOutputPath)) {
            $this->error('There is no existing documentation available at ' . $sourceOutputPath . '.');

            return false;
        }

        $this->info('Rebuilding API documentation from ' . $sourceOutputPath . '/index.md');

        $writer = new Writer($this, new DocumentationConfig(config('apidoc')));
        $writer->writeHtmlDocs();
    }
}
