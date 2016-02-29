<?php

namespace Klsandbox\LaravelCoverageReport;

class CoverageBladeCompiler extends \Illuminate\View\Compilers\BladeCompiler {

    public function compile($path = null) {

        if (str_contains($path, 'resources/views/'))
        {
            $name = explode('resources/views/', $path)[1];
        }
        else
        {
            $name = explode('/views/', $path)[1];
        }

        $record = CoverageRecord::RecordEntry('View', "", $name);
        
        parent::compile($path);
    }

    public function isExpired($path) {
        return true;
    }

}
