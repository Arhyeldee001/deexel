<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Section extends Model
{
    protected $fillable = ['page_id', 'type', 'name', 'content', 'sort_order', 'is_active'];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function value(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }
}
