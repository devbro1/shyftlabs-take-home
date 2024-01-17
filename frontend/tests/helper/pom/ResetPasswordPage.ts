import BasePage from './BasePage';
import { faker } from '@faker-js/faker';

class ResetPasswordPage extends BasePage
{
    setDefaultFormValues(data = {})
    {
        const base_data = {
            email: () => { return faker.internet.email() },
            password: () => { return "goodpassword"; },
            password_confirmation: () => { return "goodpassword"; },
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

    async submitForm(selector = 'input[type=submit]')
    {
        await this.page.click(selector);
    }
};

export default ResetPasswordPage;