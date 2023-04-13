import { PlaywrightTestConfig } from '@playwright/test';

const config: PlaywrightTestConfig = {
    use: {
        baseURL: 'http://localhost/',
        browserName: 'chromium',
        headless: true,
        screenshot: 'only-on-failure',
    },
    workers: 3
};
export default config;
