import BasePage from './BasePage';
import { faker } from '@faker-js/faker';

export default class RolePage extends BasePage {
    async goto(data = {}) {
        let url = 'roles/new'
        if(data['id'])
        {
            url = 'roles/' + data.id;
        }
        this.page.goto(url);
    }

    setDefaultFormValues(data = {}) {
        const base_data = {
            name: () => { return faker.lorem.words(2); },
            description: () => { return faker.lorem.words(4); },
        };

        for (const [key, value] of Object.entries(base_data)) {
            if (typeof data[key] === "undefined") {
                data[key] = value();
            }
        }
        return data;
    }

    async submitForm(selector = 'input[type=submit]') {
        await this.page.click(selector);
    }
};
