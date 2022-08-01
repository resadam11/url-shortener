<?php
namespace App\GraphQL\Queries\RedirectLog;

use App\Models\RedirectLog;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use DB;
use Carbon\Carbon;

class RedirectLogsQuery extends Query
{
    protected $attributes = [
        'name' => 'redirect_logs',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('RedirectLog'));
    }

    public function resolve($root, $args)
    {
        $redirect_logs = DB::table('redirect_logs')
        ->select(DB::raw('DATE_FORMAT(created_at, "%Y.%m.%e.") as date'), 'ip_address', 'browser', 'slug', 'link', DB::raw( 'count(*) as total'))
        ->groupBy('date')
        ->groupBy('ip_address')
        ->groupBy('browser')
        ->groupBy('slug')
        ->groupBy('link')
        ->get();

        return $redirect_logs;
    }
}