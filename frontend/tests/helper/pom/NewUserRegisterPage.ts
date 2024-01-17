import BasePage from './BasePage';
import faker from 'faker';

class NewUserRegisterPage extends BasePage {
    async goto(data = {}) {
        await this.page.goto('auth/sign-up');
    }

    setDefaultFormValues(data = {}) {
        let card = faker.helpers.createCard();
        card['password'] = "goodpassword";

        const base_data = {
            full_name: () => { return card.name; },
            username: () => { return card.username; },
            email: () => { return card.email; },
            // address: () => { return card.address.streetB; },
            // city: () => { return card.address.city; },
            // postal_code: () => { return card.address.zipcode; },
            // province_code: () => { return 'ON'; },
            // country_code: () => { return 'CA'; },
            // phone1: () => { return faker.phone.phoneNumberFormat(1); },
            // phone2: () => { return ''; },
            password: () => {return card.password; },
            password_confirmation: () => {return card.password; },
        };

        for (const [key, value] of Object.entries(base_data)) {
            if (typeof data[key] === "undefined") {
                data[key] = value();
            }
        }
        return data;
    }
    async submitForm(selector = 'input[type=submit][name=submit]') {
        await this.page.click(selector);
    }
};

export default NewUserRegisterPage;