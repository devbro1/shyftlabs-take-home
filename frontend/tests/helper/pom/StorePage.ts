import BasePage from './BasePage';
import faker from 'faker';
faker.locale = 'en_CA';

class StorePage extends BasePage {
    async goto(data = {}) {
        let url = 'stores/new'
        if(data['id'])
        {
            url = 'stores/' + data.id;
        }
        this.page.goto(url);
    }

    setDefaultFormValues(data = {}) {
        let card = faker.helpers.createCard();
        const base_data = {
            active: () => { return 1; },
            store_no: () => { return faker.datatype.number(); },
            name: () => { return faker.lorem.words(2); },

            address: () => { return card.address.streetB; },
            city: () => { return card.address.city; },
            postal_code: () => { return faker.random.arrayElement(['M2J1B1','N2J1B1','L3T1E1','K3S2N1']);/* card.address.zipcode; */ },
            province_code: () => { return 'ON'; },
            country_code: () => { return 'CA'; },

            longitude: () => { return '';/*faker.address.longitude();*/ },
            latitude: () => { return '';/*faker.address.latitude();*/ },
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

export default StorePage;