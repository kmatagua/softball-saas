import { createApp, ref, computed, onMounted } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import ScorekeeperApp from './components/ScorekeeperApp.js';

export const mountScorekeeper = (mountPoint, apiEndpoints) => {
    const app = createApp({
        components: {
            ScorekeeperApp
        },
        setup() {
            // Pasamos los endpoints a proveer a los componentes hijos
            return { apiEndpoints };
        },
        template: '<ScorekeeperApp :endpoints="apiEndpoints" />'
    });

    app.mount(mountPoint);
};
