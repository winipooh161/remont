<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectScheduleItem;
use Illuminate\Http\Request;

class ProjectScheduleController extends Controller
{
    /**
     * Получить список элементов графика для проекта
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $items = $project->scheduleItems()->orderBy('type')->orderBy('position')->get();
        
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }
    
    /**
     * Получить конкретный элемент графика
     */
    public function show(ProjectScheduleItem $item)
    {
        $this->authorize('view', $item->project);
        
        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }
    
    /**
     * Сохранить новый элемент графика
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
        $position = $project->scheduleItems()
            ->where('type', $validated['type'])
            ->max('position') + 1;
            
        $item = $project->scheduleItems()->create([
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
     * Обновить существующий элемент графика
     */
    public function update(Request $request, ProjectScheduleItem $item)
    {
        $this->authorize('update', $item->project);
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'paid_amount' => 'sometimes|required|numeric|min:0',
        ]);
        
        $item->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Элемент успешно обновлен',
            'data' => $item
        ]);
    }
    
    /**
     * Обновить порядок элементов графика
     */
    public function updatePositions(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:project_schedule_items,id',
            'items.*.position' => 'required|integer|min:0',
        ]);
        
        foreach ($request->items as $itemData) {
            ProjectScheduleItem::where('id', $itemData['id'])
                ->where('project_id', $project->id)
                ->update(['position' => $itemData['position']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Порядок элементов обновлен'
        ]);
    }
    
    /**
     * Удалить элемент графика
     */
    public function destroy(ProjectScheduleItem $item)
    {
        $this->authorize('update', $item->project);
        
        $item->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Элемент успешно удален'
        ]);
    }
}
