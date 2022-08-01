<?php
namespace App\GraphQL\Types;

use App\Models\ShortLink;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ShortLinkType extends GraphQLType
{
    protected $attributes = [
        'name' => 'ShortLink',
        'description' => 'Collection of short links',
        'model' => ShortLink::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID of short link'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Slug of the short link'
            ],
            'link' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Link of the short link'
            ],
            'status_code' => [
                'type' => Type::string(),
                'description' => 'Status code of the short link'
            ]
        ];
    }
}