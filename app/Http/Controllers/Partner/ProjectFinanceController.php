<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFinanceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectFinanceController extends Controller
{
    /**
     * Получить список элементов финансов для проекта
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        // Добавляем логирование для отладки
        Log::info('Запрос на получение финансовых данных', [
            'project_id' => $project->id,
            'route' => request()->route()->getName()
        ]);
        
        $items = $project->financeItems()->orderBy('type')->orderBy('position')->get();
        
        // Логируем количество найденных записей
        Log::info('Найдено финансовых записей: ' . $items->count());
        
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
    
    /**
     * Получить конкретный элемент финансов
     */
    public function show(ProjectFinanceItem $item)
    {
        $this->authorize('view', $item->project);
        
        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }
    
    /**
     * Сохранить новый элемент финансов
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'type' => 'required|string|in:main_work,main_material,additional_work,additional_material,transportation',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);
        
        // Определение позиции для нового элемента (последняя + 1)
        $position = $project->financeItems()
            ->where('type', $validated['type'])
            ->max('position') + 1;
            
        $item = $project->financeItems()->create([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'total_amount' => $validated['total_amount'],
            'paid_amount' => $validated['paid_amount'],
            'position' => $position,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Элемент успешно добавлен',
            'data' => $item
        ]);
    }
    
    /**
     * Обновить существующий элемент финансов
     */
    public function update(Request $request, ProjectFinanceItem $item)
    {
        $this->authorize('update', $item->project);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'paid_amount' => 'sometimes|required|numeric|min:0',
            'type' => 'sometimes|string|in:main_work,main_material,additional_work,additional_material,transportation',
        ]);
        
        $item->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Элемент успешно обновлен',
            'data' => $item
        ]);
    }
    
    /**
     * Обновить порядок элементов финансов
     */
    public function updatePositions(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:project_finance_items,id',
            'items.*.position' => 'required|integer|min:0',
        ]);
        
        foreach ($request->items as $itemData) {
            ProjectFinanceItem::where('id', $itemData['id'])
                ->where('project_id', $project->id)
                ->update(['position' => $itemData['position']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Порядок элементов обновлен'
        ]);
    }
    
    /**
     * Удалить элемент финансов
     */
    public function destroy(ProjectFinanceItem $item)
    {
        $this->authorize('update', $item->project);
        
        $item->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Элемент успешно удален'
        ]);
    }
    
    /**
     * Экспорт данных в Excel
     */
    public function export(Project $project)
    {
        $this->authorize('view', $project);
        
        // Здесь должен быть код для экспорта в Excel
        // Пока просто возвращаем заглушку
        return response()->json([
            'success' => true,
            'message' => 'Функция экспорта находится в разработке'
        ]);
    }
}
