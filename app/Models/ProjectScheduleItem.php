<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectScheduleItem extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'name',
        'status',
        'start_date',
        'end_date',
        'days',
        'position',
        'is_zakupka',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'integer',
        'position' => 'integer',
        'is_zakupka' => 'boolean',
    ];

    /**
     * Получить проект, к которому принадлежит элемент графика.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
    /**
     * Получить текстовый статус элемента.
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case 'completed':
                return 'Готово';
            case 'in_progress':
                return 'В работе';
            default:
                return 'Ожидание';
        }
    }
}
