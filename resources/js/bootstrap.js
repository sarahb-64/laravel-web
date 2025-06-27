import axios from 'axios';
import { Ziggy } from './ziggy';
import { route } from 'ziggy-js';

// Configuración de Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.timeout = 30000; // 30 seconds timeout

// Configuración de Ziggy
window.Ziggy = Ziggy;
window.route = route;