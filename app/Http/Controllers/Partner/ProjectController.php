<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Отображает список объектов
     */
    public function index(Request $request)
    {
        // Получаем параметры фильтрации и поиска
        $filters = $request->only(['status', 'work_type', 'branch', 'search']);
        
        // Если есть параметр clear=true, очищаем фильтры
        if ($request->has('clear')) {
            session()->forget('project_filters');
            return redirect()->route('partner.projects.index');
        }
        
        // Если пришли новые фильтры, сохраняем их в сессию
        if ($request->has('filter')) {
            session()->put('project_filters', $filters);
        } else {
            // Иначе используем фильтры из сессии или пустой массив
            $filters = session('project_filters', []);
        }
        
        // Запрос проектов с учетом фильтров
        $projectsQuery = Project::where('partner_id', Auth::id());
        
        // Применяем фильтр по статусу
        if (!empty($filters['status'])) {
            $projectsQuery->where('status', $filters['status']);
        }
        
        // Применяем фильтр по типу работ
        if (!empty($filters['work_type'])) {
            $projectsQuery->where('work_type', $filters['work_type']);
        }
        
        // Применяем фильтр по филиалу
        if (!empty($filters['branch'])) {
            $projectsQuery->where('branch', $filters['branch']);
        }
        
        // Применяем поиск
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $projectsQuery->where(function($query) use ($search) {
                $query->where('client_name', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('contract_number', 'LIKE', "%{$search}%");
            });
        }
        
        // Сортировка и пагинация
        $projects = $projectsQuery->orderBy('created_at', 'desc')
                                ->paginate(12)
                                ->appends($filters); // Добавляем параметры в ссылки пагинации
        
        return view('partner.projects.index', compact('projects', 'filters'));
    }

    /**
     * Показывает форму для создания нового объекта
     */
    public function create()
    {
        return view('partner.projects.create');
    }

    /**
     * Сохраняет новый объект в базе данных
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment_number' => 'nullable|string|max:50',
            'area' => 'nullable|numeric|min:0',
            'phone' => 'required|string|max:20',
            'object_type' => 'nullable|string|max:100',
            'work_type' => ['required', Rule::in(['repair', 'design', 'construction'])],
            'contract_date' => 'nullable|date',
            'contract_number' => 'nullable|string|max:50',
            'work_start_date' => 'nullable|date',
            'work_amount' => 'nullable|numeric|min:0',
            'materials_amount' => 'nullable|numeric|min:0',
            'camera_link' => 'nullable|url|max:255',
            'schedule_link' => 'nullable|url|max:255',
            'code_inserted' => 'nullable|boolean',
            'contact_phones' => 'nullable|string',
            'branch' => 'nullable|string|max:100',
        ]);
        
        $validated['partner_id'] = Auth::id();
        
        $project = Project::create($validated);
        
        return redirect()->route('partner.projects.index')
                         ->with('success', 'Объект успешно создан.');
    }

    /**
     * Отображает детальную информацию об объекте
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);
        
        return view('partner.projects.show', compact('project'));
    }

    /**
     * Показывает форму для редактирования объекта
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('partner.projects.edit', compact('project'));
    }

    /**
     * Обновляет информацию об объекте в базе данных
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment_number' => 'nullable|string|max:50',
            'area' => 'nullable|numeric|min:0',
            'phone' => 'required|string|max:20',
            'object_type' => 'nullable|string|max:100',
            'work_type' => ['required', Rule::in(['repair', 'design', 'construction'])],
            'contract_date' => 'nullable|date',
            'contract_number' => 'nullable|string|max:50',
            'work_start_date' => 'nullable|date',
            'work_amount' => 'nullable|numeric|min:0',
            'materials_amount' => 'nullable|numeric|min:0',
            'camera_link' => 'nullable|url|max:255',
            'schedule_link' => 'nullable|url|max:255',
            'code_inserted' => 'nullable|boolean',
            'contact_phones' => 'nullable|string',
            'branch' => 'nullable|string|max:100',
            'status' => ['nullable', Rule::in(['active', 'completed', 'paused', 'cancelled'])],
        ]);
        
        $project->update($validated);
        
        return redirect()->route('partner.projects.show', $project)
                         ->with('success', 'Объект успешно обновлен.');
    }

    /**
     * Удаляет объект из базы данных
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        $project->delete();
        
        return redirect()->route('partner.projects.index')
                         ->with('success', 'Объект успешно удален.');
    }
}
