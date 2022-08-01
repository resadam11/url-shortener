<?php
namespace App\GraphQL\Mutations\ShortLink;

use App\Models\ShortLink;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;

class DeleteShortLinkMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteShortLink',
        'description' => 'deletes a short link'
    ];

    public function type(): Type
    {
        return Type::boolean();
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $shortLink = ShortLink::findOrFail($args['id']);

        return  $shortLink->delete() ? true : false;
    }
}