import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';
import UserEditorPage from './helper/pom/UserEditorPage';

var app_action;
var user_editor_page;
test.describe('testing: ', () => {
  test.beforeEach(async ({ page }) => {
    app_action = new AppActions(page);
    user_editor_page = new UserEditorPage(page);

    await page.goto('/');
    await app_action.loginAsAdmin();
  });

  test('add new announcement', async ({ page }) => {
    await user_editor_page.goto();
    await user_editor_page.fillForm();
  });
});