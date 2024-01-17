import { test, expect } from '@playwright/test';
import { faker } from '@faker-js/faker';
import UserEditorPage from './helper/pom/UserEditorPage';
import AppActions from './helper/AppActions';
import PermissionPage from './helper/pom/PermissionPage';
import RolePage from './helper/pom/RolePage';
import RolesListPage from './helper/pom/RolesListPage';
import PermissionsListPage from './helper/pom/PermissionsListPage';
import GenericListPage from './helper/pom/GenericListPage';

var app_action;
var permission_page;
var permissions_list_page;
var user_editor_page;
var role_page;
var roles_list_page;
var list_page;
test.describe('Permissions and Roles: ', () => {
    test.beforeAll(() => {
        // faker.locale = 'en_CA';
    });

    test.beforeEach(async ({ page }) => {
        await page.goto('/');
        app_action = new AppActions(page);
        permission_page = new PermissionPage(page);
        permissions_list_page = new PermissionsListPage(page);
        user_editor_page = new UserEditorPage(page);
        role_page = new RolePage(page);
        roles_list_page = new RolesListPage(page);
        list_page = new GenericListPage(page, '/permissions');

        await app_action.loginAsAdmin();
        await page.waitForURL('announcements');

        // page.on('console', (msg) => {
        //     console.log('CONSOLE:');
        //     console.log(`"${msg.text()}"`);
        // });

        // page.on('response', async (response) => {
        //     if (response.url().indexOf('api') == -1) {
        //         return;
        //     }

        //     await response.finished();
        //     console.log('RESPONSE:');
        //     console.log(response.status());
        //     console.log(response.headers());
        //     try {
        //         console.log(await response.text());
        //     } catch (ex) {}
        // });
    });

    test('add new permission, edit it, can see it on role page, can see it on user_editor page', async ({ page }) => {
        //add
        await permission_page.goto();
        var values = await permission_page.fillForm();
        await permission_page.submitForm();
        await page.waitForSelector(':has-text("Permission was created successfully")');
        await permissions_list_page.goto();
        await permissions_list_page.filterTableRows({ name: values.name });
        await page.click('a:has-text("' + values.name + '")');
        //edit
        values.name = faker.lorem.words(3);
        await permission_page.fillForm(values);
        await permission_page.submitForm();
        await page.waitForSelector(':has-text("Permission was updated successfully")');
        //role page
        await role_page.goto({ id: 2 });
        await role_page.updateForm({ permissions: [values.name] });
        await page.waitForSelector(':has-text("' + values.name + '")');
        //user page
        await user_editor_page.goto();
        await user_editor_page.updateForm({ permissions: [values.name] });
        await page.waitForSelector(':has-text("' + values.name + '")');
    });

    test('cannot edit system permissions', async ({ page }) => {
        await permissions_list_page.goto();
        await permissions_list_page.filterTableRows({ name: 'Add User' });
        await page.click('tbody >> text=Add User');
        await expect(page).toHaveURL(/permissions/);
        await permission_page.fillForm();
        await permission_page.submitForm();
        await page.waitForSelector(':has-text("Cannot edit system permission")');
    });

    test('Add new role, edit it, is available to be assign to a user', async ({ page }) => {
        await role_page.goto();
        let values = await role_page.fillForm();
        await role_page.submitForm();
        await page.waitForSelector(':has-text("Role was created successfully")');
        await roles_list_page.goto();
        await roles_list_page.filterTableRows({ name: values.name });
        await page.click('a:has-text("' + values.name + '")');
        values = await role_page.fillForm();
        await role_page.submitForm();
        await page.waitForSelector(':has-text("Role was updated successfully")');
        //user page
        await user_editor_page.goto();
        await user_editor_page.fillForm({ roles: [values.name] });
        await page.click(':has-text("' + values.name + '")');
    });
});
