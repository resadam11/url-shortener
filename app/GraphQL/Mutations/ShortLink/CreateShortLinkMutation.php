<?php
namespace App\GraphQL\Mutations\ShortLink;

use App\Models\ShortLink;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Str;

class CreateShortLinkMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createShortLink',
        'description' => 'Creates a short link'
    ];

    public function type(): Type
    {
        return GraphQL::type('ShortLink');
    }

    public function args(): array
    {
        return [
            'slug' => [
                'name' => 'slug',
                'type' => Type::string()
            ],
            'link' => [
                'name' => 'link',
                'type' => Type::nonNull(Type::string())
            ],
            'status_code' => [
                'name' => 'status_code',
                'type' => Type::string()
            ]
        ];
    }

    public function resolve($root, $args)
    {
        if (!isset($args['slug']) || $args['slug'] == '') {
            $args['slug'] = strtolower(Str::random(6));
        }

        $shortLink = ShortLink::create($args);
        
        if (!$shortLink) {
            return null;
        }

        return $shortLink;
    }
}