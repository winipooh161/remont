/**
 * Отладочный скрипт для мониторинга запросов финансовой вкладки
 */
document.addEventListener('DOMContentLoaded', function() {
    // Монитор для запросов, связанных с финансами
    const financeTabElement = document.getElementById('finance-tab');
    if (financeTabElement) {
        console.log('Финансовая вкладка обнаружена. Настройка мониторинга...');
        
        // Отслеживание активации вкладки
        financeTabElement.addEventListener('shown.bs.tab', function() {
            console.log('Финансовая вкладка активирована');
            
            // Поиск элементов таблиц
            const tableIds = ['mainWorksTable', 'mainMaterialsTable', 'additionalWorksTable', 
                             'additionalMaterialsTable', 'transportationTable'];
            
            tableIds.forEach(id => {
                const element = document.getElementById(id);
                console.log(`Таблица ${id} ${element ? 'найдена' : 'не найдена'}`);
            });
            
            // Информация о проекте
            const projectTabsElement = document.getElementById('projectTabs');
            if (projectTabsElement) {
                const projectId = projectTabsElement.getAttribute('data-project-id');
                console.log(`ID проекта: ${projectId}`);
                
                // Проверка возможных URL для запросов
                const urls = [
                    `/partner/projects/${projectId}/finance`,
                    `/partner/projects/${projectId}/finance/items`,
                    `/partner/projects/${projectId}/schedule/items`
                ];
                
                console.log('Возможные URL для запросов:', urls);
            }
        });
    }
    
    // Перехватчик для всех fetch-запросов, связанных с finance или schedule
    const originalFetch = window.fetch;
    window.fetch = function() {
        const url = arguments[0];
        
        if (typeof url === 'string' && (url.includes('/finance') || url.includes('/schedule'))) {
            console.group('Finance/Schedule API Request');
            console.log('URL:', url);
            console.log('Method:', arguments[1]?.method || 'GET');
            console.groupEnd();
        }
        
        return originalFetch.apply(this, arguments).then(response => {
            if (typeof url === 'string' && (url.includes('/finance') || url.includes('/schedule'))) {
                console.group('Finance/Schedule API Response');
                console.log('URL:', url);
                console.log('Status:', response.status);
                
                // Клонируем ответ, чтобы не нарушить основной функционал
                const clone = response.clone();
                
                clone.json().then(data => {
                    console.log('Data:', data);
                    console.groupEnd();
                }).catch(e => {
                    console.log('Не удалось преобразовать ответ в JSON:', e);
                    console.groupEnd();
                });
            }
            
            return response;
        }).catch(error => {
            if (typeof url === 'string' && (url.includes('/finance') || url.includes('/schedule'))) {
                console.group('Finance/Schedule API Error');
                console.error('Error:', error);
                console.groupEnd();
            }
            throw error;
        });
    };
});
