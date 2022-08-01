<?php
namespace App\GraphQL\Queries\ShortLink;

use App\Models\ShortLink;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ShortLinkQuery extends Query
{
    protected $attributes = [
        'name' => 'short_link',
    ];

    public function type(): Type
    {
        return GraphQL::type('ShortLink');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'slug' => [
                'name' => 'slug',
                'type' => Type::string()
            ],
        ];
    }

    public function resolve($root, $args)
    {
        $where = function ($query) use ($args) {
            if (isset($args['id'])) {
                $query->where('id',$args['id']);
            }

            if (isset($args['slug'])) {
                $query->where('slug',$args['slug']);
            }
        };
        
        if(!isset($args['id']) && !isset($args['slug'])){
            return null;
        }
        
        return ShortLink::where($where)->first();
    }
}