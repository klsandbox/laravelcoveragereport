<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Console\Command;

class CoverageRunStart extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coverage:runstart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Record the start of a coverage run.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (CoverageRun::getActiveRun()) {
            $this->error('Error: Active runs exists');

            return;
        }

        $run = new CoverageRun();
        $run->name = 'Run';
        $run->active = true;
        $run->save();
    }
}
