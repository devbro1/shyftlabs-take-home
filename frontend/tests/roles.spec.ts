import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';
import RolePage from './helper/pom/RolePage';
import RolesListPage from './helper/pom/RolesListPage';

var app_action;
var role_page;
var roles_list_page;
test.describe('Store: ', () => {

    test.beforeAll(() => {
      faker.locale = 'en_CA';
    })
  
    test.beforeEach(async ({ page }) => {
      await page.goto('/');
      app_action = new AppActions(page);
      role_page = new RolePage(page);
      roles_list_page = new RolesListPage(page);

      await app_action.loginAsAdmin();
      await page.waitForURL('announcements');
    });

    test('add new role, edit it', async ({ page }) => {
        //add
        await role_page.goto()
        var values = await role_page.fillForm()
        await role_page.submitForm()
        await page.waitForSelector(':has-text("Role was created successfully")');
        await roles_list_page.goto()
        await roles_list_page.filterTableRows(values)
        await page.click('a:has-text("'+values.name+'")');
        //edit
        values.name = faker.lorem.words(2)
        await role_page.fillForm(values)
        await role_page.submitForm()
        await page.waitForSelector(':has-text("Role was updated successfully")');
    });
})