import { createApp } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js';
import FanLiveView from './components/FanLiveView.js';

export const mountFanView = (mountPoint, apiEndpoints) => {
    const app = createApp({
        components: {
            FanLiveView
        },
        setup() {
            return { apiEndpoints };
        },
        template: '<FanLiveView :endpoints="apiEndpoints" />'
    });

    app.mount(mountPoint);
};
