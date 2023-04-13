import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';
import StorePage from './helper/pom/StorePage';
import StoresListPage from './helper/pom/StoresListPage';

var app_action;
var store_page;
var stores_list_page;
test.describe('Store: ', () => {

    test.beforeAll(() => {
      faker.locale = 'en_CA';
    })
  
    test.beforeEach(async ({ page }) => {
      await page.goto('/');
      app_action = new AppActions(page);
      store_page = new StorePage(page);
      stores_list_page = new StoresListPage(page);

      await app_action.loginAsAdmin();
      await page.waitForURL('announcements');
    });

    test('add new store, edit it', async ({ page }) => {
        //add
        await store_page.goto()
        var values = await store_page.fillForm()
        await store_page.submitForm()
        await page.waitForSelector(':has-text("Store was created successfully")');
        await stores_list_page.goto()
        await stores_list_page.filterTableRows(values)
        await page.locator('td:has-text("'+values.name+'") >> xpath=.. >> a').click();
        //console.log(await loc.innerHTML());

        //edit
        values.name = faker.lorem.words(2)
        await store_page.fillForm(values)
        await store_page.submitForm()
        await page.waitForSelector(':has-text("Store was updated successfully")');
    });
})  