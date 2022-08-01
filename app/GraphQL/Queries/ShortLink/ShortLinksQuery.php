<?php
namespace App\GraphQL\Queries\ShortLink;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use App\Models\ShortLink;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ShortLinksQuery extends Query
{
    protected $attributes = [
        'name' => 'short_links',
    ];

    public function type(): Type
    {
        return GraphQL::paginate('ShortLink');
    }

    public function args(): array
    {
        return [
            'limit' => [
                'name' => 'limit',
                'type' => Type::int()
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int()
            ],
            'filter' => [
                'name' => 'filter',
                'type' => Type::string()
            ]
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $info, Closure $getSelectFields)
    {
        $where = function ($query) use ($args) {
            if (isset($args['filter'])) {
                $query->where('link', 'LIKE', '%' . $args['filter'] . '%');
            }
        };

        $fields = $getSelectFields();

        return ShortLink::with($fields->getRelations())
            ->where($where)
            ->select($fields->getSelect())
            ->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}