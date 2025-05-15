// JavaScript для управления боковой панелью

document.addEventListener('DOMContentLoaded', function() {
    // Кнопка показа боковой панели на мобильных устройствах
    const sidebarCollapseShow = document.getElementById('sidebarCollapseShow');
    if (sidebarCollapseShow) {
        sidebarCollapseShow.addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('content').classList.add('active');
        });
    }

    // Кнопка скрытия боковой панели на мобильных устройствах
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('content').classList.remove('active');
        });
    }

    // Скрывать меню при клике вне его на мобильных устройствах
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const sidebarCollapseShow = document.getElementById('sidebarCollapseShow');

        // Проверяем, не является ли элемент, на который кликнули, частью сайдбара или кнопкой открытия
        if (window.innerWidth <= 768 && 
            sidebar && 
            content &&
            !sidebar.contains(event.target) && 
            !sidebarCollapseShow.contains(event.target) && 
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            content.classList.remove('active');
        }
    });
    
    // Обработка кликов по выпадающим меню в сайдбаре
    const dropdownToggles = document.querySelectorAll('.sidebar .dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        // Удаляем обработчик Bootstrap по умолчанию
        toggle.removeAttribute('data-bs-toggle');
        
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Предотвращаем всплытие события
            
            // Получаем идентификатор целевого меню из атрибута href
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            
            if (target) {
                // Закрываем все другие открытые меню
                const openMenus = document.querySelectorAll('.sidebar ul.collapse.show');
                openMenus.forEach(menu => {
                    if (menu.id !== targetId.substring(1)) {
                        menu.classList.remove('show');
                        const parentLink = document.querySelector(`a[href="#${menu.id}"]`);
                        if (parentLink) {
                            parentLink.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
                
                // Переключаем состояние текущего меню
                const isExpanded = target.classList.contains('show');
                if (isExpanded) {
                    target.classList.remove('show');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    target.classList.add('show');
                    this.setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
});
