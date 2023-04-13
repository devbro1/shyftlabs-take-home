import BasePage from './BasePage';
import faker from 'faker';
faker.locale = 'en_CA';

export default class UserCreateLeadPage extends BasePage {
    async goto(data = {}) {
        let url = 'leads/new'
        this.page.goto(url)
    }

    setDefaultFormValues(data = {}) {
        let card = faker.helpers.createCard();
        const base_data = {
            first_name: () => { return card.name.split(" ")[0]; },
            last_name: () => { return card.name.split(" ")[1]; },
            // username: () => { return card.username; },
            email: () => { return card.email; },
            address: () => { return card.address.streetB; },
            city: () => { return card.address.city; },
            postal_code: () => { return faker.random.arrayElement(['M2J1B1','N2J1B1','L3T1E1','N3A0A7']); },
            province_code: () => { return 'ON'; },
            //country_code: () => { return 'CA'; },
            phone1: () => { return faker.phone.phoneNumberFormat(1); },
            service_id: () => { return this.selectRandomOption({field: 'service_id',skip_from_start: 1})}
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

