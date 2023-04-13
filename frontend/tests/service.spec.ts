import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';
import ServicePage from './helper/pom/ServicePage';
import ServicesListPage from './helper/pom/ServicesListPage';

var app_action;
var service_page;
var services_list_page;
test.describe('Service: ', () => {

    test.beforeAll(() => {
      faker.locale = 'en_CA';
    })
  
    test.beforeEach(async ({ page }) => {
      await page.goto('/');
      app_action = new AppActions(page);
      service_page = new ServicePage(page);
      services_list_page = new ServicesListPage(page);

      await app_action.loginAsAdmin();
      await page.waitForURL('announcements');
    });

    test('add new service, edit it', async ({ page }) => {
        //add
        await service_page.goto()
        await service_page.submitForm()
        await page.waitForSelector(':has-text("name is a required field")');
        var values = await service_page.fillForm()
        await service_page.submitForm()
        await page.waitForSelector(':has-text("Service was created successfully")');
        await services_list_page.goto()
        await services_list_page.filterTableRows(values)
        await page.locator('td:has-text("'+values.name+'") >> xpath=.. >> a').click();
        //console.log(await loc.innerHTML());

        //edit
        values.name = faker.lorem.words(2)
        await service_page.fillForm(values)
        await service_page.submitForm()
        await page.waitForSelector(':has-text("Service was updated successfully")');
    });
})  