import BasePage from './BasePage';
import faker from 'faker';

export default class ServicesListPage extends BasePage
{
    async goto(data = {}) {
        let url = '/services'
        this.page.goto(url);
        await this.page.waitForURL(url);
    }
};