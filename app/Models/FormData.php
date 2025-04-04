<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormData extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'entries', 'ip_address', 'location'];

    protected function casts()
    {
        return [
            'data' => 'array',
            'entries' => 'array',
            'location' => 'array',
        ];
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('ip_address', 'like', "%{$search}%")
                ->orWhere('data->type', 'like', "%{$search}%")
                ->orWhere('data->code', 'like', "%{$search}%")
                ->orWhere('data->amount', 'like', "%{$search}%")
                ->orWhere('entries->email', 'like', "%{$search}%");
        })
            ->when($filters['sort'] ?? null, function ($query, $sort) {
                $query->orderBy('id', $sort);
            })
            ->when(!key_exists('sort', $filters), function ($query) {
                $query->latest('id');
            })
            ->when($filters['id'] ?? null, function ($query, $id) {
                $query->where('id', $id);
            });
    }
}
