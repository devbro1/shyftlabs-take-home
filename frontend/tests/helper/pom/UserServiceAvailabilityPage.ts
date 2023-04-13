import BasePage from './BasePage';
import faker from 'faker';
faker.locale = 'en_CA';


class UserServiceAvailabilityPage extends BasePage {

    async goto(data = {}) {
        let url = '/'
        if(data['user_id'])
        {
            url = 'users/' + data.user_id + '/service-availability';
        }
        this.page.goto(url);
    }

    getDefaultFormValueGenerators(data = {}) {

        return {
            service_id: () => {return 1; },
            store_id: () => { return [1]; },
            workflow_id: () => { return 1; },
        };
    }

    setDefaultFormValues(data = {}) {

        const base_data = this.getDefaultFormValueGenerators(data)
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
}


export default UserServiceAvailabilityPage;