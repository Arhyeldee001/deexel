<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TeamMember extends Model
{
    protected $fillable = ['name', 'role', 'bio', 'photo', 'socials', 'sort_order', 'is_active'];

    protected $casts = [
        'socials' => 'array',
        'is_active' => 'boolean',
    ];
}
