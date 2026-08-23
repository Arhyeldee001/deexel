<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Service extends Model
{
    protected $fillable = [
        'title', 'slug', 'icon', 'excerpt', 'body', 'image',
        'sort_order', 'is_active', 'meta_title', 'meta_description',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }
}
