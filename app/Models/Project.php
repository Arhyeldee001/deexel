<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'client', 'excerpt', 'body',
        'featured_image', 'gallery', 'project_url', 'completed_at',
        'is_featured', 'sort_order', 'is_active', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string { return 'slug'; }
}
