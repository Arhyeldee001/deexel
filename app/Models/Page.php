<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'is_active', 'show_in_nav', 'sort_order',
        'is_system', 'meta_title', 'meta_description', 'meta_keywords', 'og_image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function activeSections(): HasMany
    {
        return $this->sections()->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
