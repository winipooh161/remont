import './bootstrap';
import $ from 'jquery';

// Проверка загрузки Bootstrap и инициализация необходимых компонентов
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap loaded and ready');
        
        // Предотвращаем автоматическую инициализацию аккордеонов
        // Отключаем только те, которые имеют специальный класс 'custom-accordion'
        document.querySelectorAll('.accordion').forEach(accordionElement => {
            if (accordionElement.id === 'scheduleAccordion') {
                console.log('Preventing auto-init for schedule accordion');
                // Для этого аккордеона мы используем собственную реализацию
                // поэтому не создаем экземпляры Collapse
            } else {
                // Для всех остальных аккордеонов используем стандартную инициализацию
                const collapseElements = accordionElement.querySelectorAll('.accordion-collapse');
                collapseElements.forEach(collapseEl => {
                    if (!bootstrap.Collapse.getInstance(collapseEl)) {
                        new bootstrap.Collapse(collapseEl, {
                            toggle: false
                        });
                    }
                });
            }
        });
    } else {
        console.error('Bootstrap not loaded properly');
        // Попытка повторной загрузки Bootstrap
        try {
            window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.min.js');
            console.log('Bootstrap loaded successfully as fallback');
        } catch (e) {
            console.error('Error loading Bootstrap as fallback:', e);
        }
    }
});

// Импортируем наш новый скрипт автозаполнения адресов
import './address-autocomplete.js';
// Импортируем скрипт для фильтров проектов
import './project-filters.js';
// Импортируем скрипт для загрузки файлов проекта
import './project-file-upload.js';
// Импортируем скрипт для управления вкладками проекта
import './project-tabs.js';
// Импортируем скрипт для управления элементами графика работ
import './project-schedule.js';
