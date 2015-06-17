<?php

namespace Klsandbox\LaravelCoverageReport;

use Illuminate\Database\Eloquent\Model;
use App;

/**
 * App\Models\CoverageRun
 *
 * @property integer $site_id
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereSiteId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereName($value)
 * @property boolean $active
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CoverageRun whereActive($value)
 */
class CoverageRun extends Model {
    //
    
    public static function getActiveRun()
    {
        $runs = CoverageRun::where('active', '=', true)
                ->get();
        
        if ($runs->count() > 1)
        {
            App::abort(500, 'Multiple runs found');
        }
        
        return $runs->first();
    }
}
