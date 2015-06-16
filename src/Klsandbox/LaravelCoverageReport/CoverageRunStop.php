<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Console\Command;

class CoverageRunStop extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coverage:runstop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop recording the coverage run.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {
        if (!$run = CoverageRun::getActiveRun())
        {
            $this->error("No run found");
            return;
        }
        
        $run->active = false;
        $run->save();
    }

}
