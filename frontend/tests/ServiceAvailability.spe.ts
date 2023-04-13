import { test, expect } from '@playwright/test';
import UserEditorPage from './helper/pom/UserEditorPage';
import UserServiceAvailabilityPage from './helper/pom/UserServiceAvailabilityPage';
import AppActions from './helper/AppActions';
import faker from 'faker';

var app_action;
var user_editor_page;
var user_service_availability_page;
test.describe('Service Availability: ', () => {

  test.beforeAll(() => {
    faker.locale = 'en_CA';
  })

  test.beforeEach(async ({ page }) => {
    await page.goto('/');
    app_action = new AppActions(page);
    user_editor_page = new UserEditorPage(page);
    user_service_availability_page = new UserServiceAvailabilityPage(page);
    await app_action.login('farzad','password');
    await page.waitForURL('announcements');
  });

  test('check perm and link', async ({ page }) => {
      let user_id = (await user_editor_page.createNewUser()).id;
      await user_editor_page.goto({user_id: user_id})
      await expect(page.locator('a:has-text("Manage Assigned Services")')).toHaveCount(0)
      await user_editor_page.updateForm({permissions: ['Service Leads']})
      await user_editor_page.submitForm()
      await page.waitForSelector('div:has-text("User was updated successfully")');
      await page.waitForSelector('a:has-text("Manage Assigned Services")');
  });

  test('check adding and removing SAs', async ({ page }) => {
    let info = await user_editor_page.createNewUser({permissions: ['Service Leads']});
    await app_action.createNewUser()
    await user_service_availability_page.goto({user_id: info.id})
    user_service_availability_page.fillForm()
    user_service_availability_page.submitForm()
    await page.waitForSelector('div:has-text("Service Availability was updated successfully")');
    let count = page.locator('table#service_availability_list tr').count();
    await expect(count > 0).toBeTruthy()
    page.on('dialog', dialog => dialog.accept());
    await page.click('a:has-text("Delete") >> nth=0')
    await expect(page.locator('table#service_availability_list tr').count() === count - 1).toBeTruthy()
  });
});