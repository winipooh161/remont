<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'partner_id',
        'client_name',
        'address',
        'apartment_number',
        'area',
        'phone',
        'object_type',
        'work_type',
        'contract_date',
        'contract_number',
        'work_start_date',
        'work_amount',
        'materials_amount',
        'camera_link',
        'schedule_link',
        'code_inserted',
        'contact_phones',
        'branch',
        'status',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'contract_date' => 'date',
        'work_start_date' => 'date',
        'work_amount' => 'decimal:2',
        'materials_amount' => 'decimal:2',
        'code_inserted' => 'boolean',
    ];

    /**
     * Получить партнера, которому принадлежит объект.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
    
    /**
     * Получить файлы, связанные с проектом.
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Получить файлы дизайн-проекта.
     */
    public function designFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->where('file_type', 'design');
    }

    /**
     * Получить файлы схем.
     */
    public function schemeFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->where('file_type', 'scheme');
    }

    /**
     * Получить файлы документов.
     */
    public function documentFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->where('file_type', 'document');
    }

    /**
     * Получить файлы договоров.
     */
    public function contractFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->where('file_type', 'contract');
    }

    /**
     * Получить прочие файлы.
     */
    public function otherFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class)->where('file_type', 'other');
    }

    /**
     * Получить элементы графика работ и материалов проекта.
     */
    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class);
    }

    /**
     * Получить основные работы.
     */
    public function mainWorks(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class)->where('type', 'main_work')->orderBy('position');
    }

    /**
     * Получить основные материалы.
     */
    public function mainMaterials(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class)->where('type', 'main_material')->orderBy('position');
    }

    /**
     * Получить дополнительные работы.
     */
    public function additionalWorks(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class)->where('type', 'additional_work')->orderBy('position');
    }

    /**
     * Получить дополнительные материалы.
     */
    public function additionalMaterials(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class)->where('type', 'additional_material')->orderBy('position');
    }

    /**
     * Получить элементы транспортировки.
     */
    public function transportationItems(): HasMany
    {
        return $this->hasMany(ProjectScheduleItem::class)->where('type', 'transportation')->orderBy('position');
    }

    /**
     * Получить строковое представление типа работ.
     *
     * @return string
     */
    public function getWorkTypeTextAttribute(): string
    {
        $types = [
            'repair' => 'Ремонт',
            'design' => 'Дизайн',
            'construction' => 'Строительство',
        ];
        
        return $types[$this->work_type] ?? $this->work_type;
    }

    /**
     * Получить общую сумму проекта.
     *
     * @return float
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->work_amount + $this->materials_amount;
    }
}
