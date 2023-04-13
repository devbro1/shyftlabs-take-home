import BasePage from './BasePage';
import faker from 'faker';

export default class RolesListPage extends BasePage
{
    async goto(data = {}) {
        let url = '/roles'
        this.page.goto(url);
        await this.page.waitForURL(url);
    }
};