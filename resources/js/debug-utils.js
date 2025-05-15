/**
 * Утилиты для отладки JavaScript и HTTP запросов
 */

// Перехватчик fetch для логирования запросов
(function() {
    const originalFetch = window.fetch;
    
    window.fetch = function() {
        console.group('Fetch Request');
        console.log('URL:', arguments[0]);
        if (arguments[1]) {
            console.log('Options:', arguments[1]);
        }
        console.groupEnd();
        
        return originalFetch.apply(this, arguments)
            .then(response => {
                // Клонируем response, чтобы его можно было использовать дважды
                const clone = response.clone();
                
                // Логируем статус ответа
                console.group('Fetch Response');
                console.log('URL:', response.url);
                console.log('Status:', response.status);
                console.log('Status Text:', response.statusText);
                console.log('Headers:', response.headers);
                
                // Пробуем прочитать тело ответа
                clone.text().then(text => {
                    try {
                        const json = JSON.parse(text);
                        console.log('Response Body (JSON):', json);
                    } catch (e) {
                        console.log('Response Body (Text):', text.substring(0, 1000) + (text.length > 1000 ? '...' : ''));
                    }
                    console.groupEnd();
                }).catch(err => {
                    console.error('Error reading response body:', err);
                    console.groupEnd();
                });
                
                return response;
            })
            .catch(error => {
                console.group('Fetch Error');
                console.error('Error:', error);
                console.groupEnd();
                throw error;
            });
    };
    
    console.log('Fetch interceptor installed for debugging');
})();

// Перехватчик axios для логирования запросов, если axios используется
if (window.axios) {
    const axiosInterceptor = window.axios.interceptors.request.use(
        config => {
            console.group('Axios Request');
            console.log('Method:', config.method.toUpperCase());
            console.log('URL:', config.url);
            console.log('Headers:', config.headers);
            if (config.data) {
                console.log('Data:', config.data);
            }
            console.groupEnd();
            return config;
        },
        error => {
            console.group('Axios Request Error');
            console.error('Error:', error);
            console.groupEnd();
            return Promise.reject(error);
        }
    );
    
    const axiosResponseInterceptor = window.axios.interceptors.response.use(
        response => {
            console.group('Axios Response');
            console.log('Status:', response.status);
            console.log('Data:', response.data);
            console.groupEnd();
            return response;
        },
        error => {
            console.group('Axios Response Error');
            console.error('Status:', error.response ? error.response.status : 'Unknown');
            console.error('Data:', error.response ? error.response.data : error.message);
            console.groupEnd();
            return Promise.reject(error);
        }
    );
    
    console.log('Axios interceptors installed for debugging');
}

// Экспортируем утилиты
export const debugUtils = {
    logFormData(formData) {
        console.group('FormData Contents');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ':', pair[1]);
        }
        console.groupEnd();
    },
    
    logElement(element, message = 'Element Details') {
        console.group(message);
        console.log('Element:', element);
        if (element) {
            console.log('ID:', element.id);
            console.log('Classes:', element.className);
            console.log('Attributes:', element.attributes);
            console.log('Children:', element.children);
        }
        console.groupEnd();
    }
};

// Импортируйте этот файл в app.js для использования
console.log('Debug utilities loaded');
