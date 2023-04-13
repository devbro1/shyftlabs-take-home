import BasePage from './BasePage';
import faker from 'faker';

class UserEditorPage extends BasePage {
    async goto(data = {}) {
        let url = 'users/new'
        if(data['id'])
        {
            url = 'users/' + data.id;
        }
        this.page.goto(url);
    }

    setDefaultFormValues(data = {}) {
        let card = faker.helpers.createCard();
        const base_data = {
            active: () => { return true; },
            full_name: () => { return card.name; },
            username: () => { return card.username; },
            email: () => { return card.email; },
            address: () => { return card.address.streetB; },
            city: () => { return card.address.city; },
            postal_code: () => { return card.address.zipcode; },
            province_code: () => { return 'ON'; },
            country_code: () => { return 'CA'; },
            phone1: () => { return faker.phone.phoneNumberFormat(1); },
            phone2: () => { return ''; },
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

    async createNewUser(data={})
    {
        data = this.setDefaultFormValues(data)
        const r = await this.page.request.post('/api/v1/users',{form:data})

        if(r.ok())
        {
            return (await r.json()).data
        }
        return false;
    }
};

export default UserEditorPage;