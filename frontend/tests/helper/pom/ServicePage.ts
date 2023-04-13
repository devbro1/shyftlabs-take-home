import BasePage from './BasePage';
import faker from 'faker';
faker.locale = 'en_CA';

class ServicePage extends BasePage {
    async goto(data = {}) {
        let url = 'services/new'
        if(data['id'])
        {
            url = 'services/' + data.id;
        }
        this.page.goto(url);
    }

    setDefaultFormValues(data = {}) {
        let card = faker.helpers.createCard();
        const base_data = {
            active: () => { return 1; },
            name: () => { return faker.lorem.words(2); },
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

export default ServicePage;