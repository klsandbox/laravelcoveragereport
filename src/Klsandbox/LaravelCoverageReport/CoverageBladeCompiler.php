<?php

namespace Klsandbox\LaravelCoverageReport;

class CoverageBladeCompiler extends \Illuminate\View\Compilers\BladeCompiler
{

    public function compile($path = null)
    {

        if (str_contains($path, 'vendor/')) {
            preg_match('~/vendor/\w+/([^/]+)~', $path, $matches);
            preg_match('~/([^/]+)$~', $path, $matches2);
            $name = $matches[1] . '::' . $matches2[1];
        } elseif (str_contains($path, 'resources/views/')) {
            $name = explode('resources/views/', $path)[1];
        } else {
            $name = explode('/views/', $path)[1];
        }

        $record = CoverageRecord::RecordEntry('View', "", $name);

        parent::compile($path);
    }

    public function isExpired($path)
    {
        return true;
    }

}
