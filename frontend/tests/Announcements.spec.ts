import { test, expect } from '@playwright/test';
import faker from 'faker';
import AppActions from './helper/AppActions';

var app_action;
test.describe('Announcement: ', () => {
  test.beforeEach(async ({ page }) => {
    app_action = new AppActions(page);

    await page.goto('/');
    await app_action.loginAsAdmin();
  });

  test('add new announcement', async ({ page }) => {
    await page.click('text=Add Announcement');
    await expect(page).toHaveURL('http://localhost/announcements/new');
    await page.waitForTimeout(3000);

    let title = faker.lorem.words()
    let body = faker.lorem.sentences()
    await page.fill('input[name="title"]', title);
    
    for (const c of body) {
        await page.press('.public-DraftEditor-content', c);
    }

    // await page.click('iframe');
    // await page.fill('iframe', body);
    await page.click('input[type="submit"]');
    await page.waitForSelector('text=Announcement was created successfully')

    await page.goto('/announcements');
    await app_action.hasText(title);
    await app_action.hasText(body);
  });
});