<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Database\Eloquent\Model;
use Config;
use Klsandbox\SiteModel\Site;

/**
 * App\Models\CoverageRecord
 *
 * @property integer $coverage_run_id
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $category
 * @property string $namespace
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereCoverageRunId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereNamespace($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRecord whereName($value)
 */
class CoverageRecord extends Model {
    //
    
    public static function RecordEntry($category, $namespace, $name)
    {
        if (!Config::get('coverage.enabled'))
        {
            return;
        }
        
        $run = CoverageRun::getActiveRun();
        if (!$run)
        {
            \App::abort(404, 'Coverage Run not found');
        }
        
        $record = new CoverageRecord();
        $record->category = $category;
        $record->coverage_run_id = $run->id;
        $record->namespace = $namespace;
        $record->name = $name;
        $record->save();
        
        return $record;
    }
}
