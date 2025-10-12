import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Axios interceptors para logging en consola (debug)
window.axios.interceptors.request.use((config) => {
    try {
        const { method, url, params, data } = config;
        // Evitar loguear passwords
        const safeData = data && typeof data === 'object' ? { ...data } : data;
        if (safeData && typeof safeData === 'object') {
            delete safeData.password;
            delete safeData.clave;
            delete safeData.token;
        }
        console.debug('[HTTP Req]', method?.toUpperCase(), url, { params, data: safeData });
    } catch (e) {
        // noop
    }
    return config;
});

window.axios.interceptors.response.use(
    (response) => {
        try {
            const { status, config } = response;
            console.debug('[HTTP Res]', status, config?.url);
        } catch (e) {
            // noop
        }
        return response;
    },
    (error) => {
        try {
            const status = error?.response?.status;
            const url = error?.config?.url;
            const data = error?.response?.data;
            console.error('[HTTP Err]', status, url, data);
        } catch (e) {
            // noop
        }
        return Promise.reject(error);
    }
);
