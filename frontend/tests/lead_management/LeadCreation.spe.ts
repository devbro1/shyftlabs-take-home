import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from '../helper/AppActions';
import UserCreateLeadPage from '../helper/pom/UserCreateLeadPage';
import LeadsListPage from '../helper/pom/LeadsListPage';

let app_action: AppActions;
let user_create_lead_page: UserCreateLeadPage;
let leads_list_page: LeadsListPage;

test.describe('Lead Creation: ', () => {
  test.beforeEach(async ({ page }) => {
    app_action = new AppActions(page);
    user_create_lead_page = new UserCreateLeadPage(page);
    leads_list_page = new LeadsListPage(page);

    await page.goto('/');
  });

  test('user can navigte to the page', async ({page}) => {
    await app_action.login('farzad','password');
    await page.click('text=Leads');
    await page.click('text=Create Lead');
    await expect(page).toHaveURL('/leads/new');
  });

  test('add new Lead as User', async ({ page }) => {
    await app_action.login('farzad','password');
    await user_create_lead_page.goto();
    await expect(page).toHaveURL('/leads/new');
    
    let lead_info = await user_create_lead_page.fillForm();
    await user_create_lead_page.submitForm();
    await app_action.contains('Lead was created successfully');

    await leads_list_page.goto();
    await expect(page).toHaveURL('/leads');
    await app_action.contains(lead_info.first_name);
    await app_action.contains(lead_info.last_name);
  });
});