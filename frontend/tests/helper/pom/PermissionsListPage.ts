import BasePage from './BasePage';
import faker from 'faker';

export default class PermissionsListPage extends BasePage
{
    async goto(data = {}) {
        let url = '/permissions'
        this.page.goto(url);
        await this.page.waitForURL(url);
    }

    setDefaultFormValues(data = {})
    {
        return data;
    }

    async submitForm(selector = 'input[type=submit][value=Upload]')
    {
        await this.page.click(selector);
    }
};