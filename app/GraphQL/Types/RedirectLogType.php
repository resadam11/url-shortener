<?php
namespace App\GraphQL\Types;

use App\Models\RedirectLog;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class RedirectLogType extends GraphQLType
{
    protected $attributes = [
        'name' => 'RedirectLog',
        'description' => 'Collection of redirect logs',
        'model' => RedirectLog::class
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
                'description' => 'Slug of the redirect log'
            ],
            'link' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Link of the redirect log'
            ],
            'ip_address' => [
                'type' =>Type::nonNull(Type::string()),
                'description' => 'Ip address of the redirect log'
            ],
            'browser' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Browser of the redirect log'
            ],
            'date' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Created at of the redirect log'
            ],
            'total' => [
                'type' => Type::int(),
                'description' => 'Created at of the redirect log'
            ]
        ];
    }
}