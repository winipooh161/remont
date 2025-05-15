/**
 * Скрипт для управления фильтрацией проектов
 */
document.addEventListener('DOMContentLoaded', function() {
    // Получаем форму фильтрации, если она существует на странице
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;
    
    // Управление отображением активных фильтров
    const updateFilterBadges = () => {
        const filterBadgesContainer = document.getElementById('active-filters');
        if (!filterBadgesContainer) return;
        
        // Очищаем контейнер
        filterBadgesContainer.innerHTML = '';
        
        // Получаем все активные фильтры
        const activeFilters = [];
        
        // Проверяем поиск
        const searchInput = filterForm.querySelector('input[name="search"]');
        if (searchInput && searchInput.value.trim()) {
            activeFilters.push({
                type: 'search',
                label: `Поиск: ${searchInput.value.trim()}`,
                value: searchInput.value.trim()
            });
        }
        
        // Проверяем выбранные селекты
        filterForm.querySelectorAll('select').forEach(select => {
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                activeFilters.push({
                    type: select.name,
                    label: `${select.previousElementSibling?.textContent || ''}: ${selectedOption.textContent}`,
                    value: select.value
                });
            }
        });
        
        // Если есть активные фильтры, показываем их
        if (activeFilters.length > 0) {
            filterBadgesContainer.innerHTML = '<span class="me-2">Активные фильтры:</span>';
            
            activeFilters.forEach(filter => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-light text-dark me-2 mb-1';
                badge.textContent = filter.label;
                filterBadgesContainer.appendChild(badge);
            });
        }
    };
    
    // Вызываем функцию при загрузке страницы
    updateFilterBadges();
});
