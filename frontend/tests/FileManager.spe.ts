import { test, expect } from '@playwright/test';
import AppActions from './helper/AppActions';
import FileManagerPage from './helper/pom/FileManagerPage';
import faker from 'faker';

var app_action;
var file_manager_page: FileManagerPage;
test.describe('File Management ', () => {

    test.beforeAll(() => {
        faker.locale = 'en_CA';
    })

    test.beforeEach(async ({ page }) => {
        await page.goto('/');
        app_action = new AppActions(page);
        file_manager_page = new FileManagerPage(page);
        await app_action.loginAsAdmin();
    });

    test('Can upload file successfully', async ({ page }) => {
        await file_manager_page.goto()

        await file_manager_page.submitForm()
        await app_action.hasText('file is required')
        await file_manager_page.fillForm()        
        await page.waitForTimeout(3000);
        await file_manager_page.submitForm()
        //await page.waitForTimeout(10000);
        //test can download the file

    });

    test('can delete file', async ({ page }) => {
        await file_manager_page.goto()
        await page.waitForTimeout(3000);
        let a = await page.$('td a');
        let ida = await a.textContent();

        page.once('dialog', dialog => {
            dialog.accept().catch(() => {});
          });

        await page.click('button:has-text("Delete")');

        await page.waitForTimeout(3000);
        let b = await page.$eval('td a', node => node.innerText);

        expect(ida !== b).toBeTruthy();
    });

    test('regular user cannot access file', async ({ page }) => {
        await file_manager_page.goto()
        await page.waitForTimeout(3000);
        let a = await page.$('td a');
        let ida = await a.textContent();

        await app_action.logout()
        let response = await page.goto('api/v1/files/' + ida)
        expect(response.status()).toEqual(401)
    });
});