<?php

namespace KimSpeer\Sort\Commands;

use Illuminate\Console\Command;

class SortCommand extends Command
{
    public $signature = 'sortlist';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
