import BasePage from './BasePage';
import faker from 'faker';

class LoginPage extends BasePage
{
    async goto(data = {}) {
        let url = '/settings/file-manager'
        this.page.goto(url);
        await this.page.waitForURL(url);
    }

    setDefaultFormValues(data = {})
    {
        const base_data = {
            file: () => { return {name: faker.system.commonFileName(),
                            buffer: "test text"}
                },
        };


        for (const [key, value] of Object.entries(base_data))
        {
            if(typeof data[key] === "undefined")
            {
                data[key] = value();
            }
        }

        return data;
    }

    async submitForm(selector = 'input[type=submit][value=Upload]')
    {
        await this.page.click(selector);
    }
};

export default LoginPage;