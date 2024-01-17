import BasePage from './BasePage';
import { faker } from '@faker-js/faker';

class LoginPage extends BasePage
{
    async goto(data = {}) {
        let url = 'auth/SignIn'
        this.page.goto(url);
    }

    setDefaultFormValues(data = {})
    {
        const base_data = {
            login: () => { return faker.internet.login() },
            password: () => { return "badpassword"; },
        };


        for (const [key, value] of Object.entries(base_data))
        {
            if(typeof data[key] === undefined)
            {
                data[key] = base_data[value]();
            }
        }
        return data;
    }

    async submitForm(selector = 'text=Sign In')
    {
        await this.page.locator(selector).click();
    }
};

export default LoginPage;