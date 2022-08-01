<?php
namespace App\GraphQL\Mutations\ShortLink;

use App\Models\ShortLink;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateShortLinkMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateShortLink',
        'description' => 'Updates a short link'
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
                'type' =>  Type::nonNull(Type::int()),
            ],
            'slug' => [
                'name' => 'slug',
                'type' => Type::nonNull(Type::string())
            ],
            'link' => [
                'name' => 'link',
                'type' => Type::nonNull(Type::string())
            ],
            'status_code' => [
                'name' => 'status_code',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $shortLink = ShortLink::findOrFail($args['id']);
        $shortLink->fill($args);
        $shortLink->save();

        return $shortLink;
    }
}