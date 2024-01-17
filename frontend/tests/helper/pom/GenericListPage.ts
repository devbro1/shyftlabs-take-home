import BasePage from './BasePage';
import faker from 'faker';

export default class GenericListPage extends BasePage
{
    base_url: string;
    constructor(page,base_url:string) {
        super(page);
        this.base_url = base_url;
    }

    async goto(data = {}) {
        this.page.goto(this.base_url);
        await this.page.waitForURL(this.base_url);
    }
};