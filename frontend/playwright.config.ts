import { PlaywrightTestConfig, expect } from '@playwright/test';

const config: PlaywrightTestConfig = {
    use: {
        baseURL: 'http://localhost:3000/',
        browserName: 'chromium',
        headless: true,
        screenshot: 'only-on-failure',
        actionTimeout: 5000,
    },
    workers: 3,
    timeout: 60000,
};

expect.extend({
    async toBeAnyOf(received, possibilities) {
        if (possibilities.includes(received)) {
            return {
                message: () => 'passed',
                pass: true,
            };
        }

        return {
            message: () => `failed: '${received}' was not any of ${possibilities}`,
            pass: false,
        };
    },
});

export default config;
