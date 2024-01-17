import { test, expect } from '@playwright/test';
import UserEditorPage from './helper/pom/UserEditorPage';
import NewUserRegisterPage from './helper/pom/NewUserRegisterPage';
import ResetPasswordPage from './helper/pom/ResetPasswordPage';
import ForgotPasswordPage from './helper/pom/ForgotPasswordPage';
import AppActions from './helper/AppActions';
import { faker } from '@faker-js/faker';

var app_action;
var user_editor_page;
var new_user_register_page;
var reset_password_page;
var forgot_password_page;
test.describe('User Editor: ', () => {
    test.beforeAll(() => {
    });

    test.beforeEach(async ({ page }) => {
        await page.goto('/');
        app_action = new AppActions(page);
        user_editor_page = new UserEditorPage(page);
        new_user_register_page = new NewUserRegisterPage(page);
        reset_password_page = new ResetPasswordPage(page);
        forgot_password_page = new ForgotPasswordPage(page);
        await app_action.login('farzad', 'password');
        await page.waitForURL('**/announcements');
    });

    test('Add new user', async ({ page }) => {
        await user_editor_page.goto();
        let user_info = await user_editor_page.fillForm();
        await user_editor_page.submitForm();
        await page.waitForSelector(':has-text("User was created successfully")');

        //assert email went out
        let emails = await app_action.getSentEmails({ to: user_info.email });
        expect(emails.length > 0).toBeTruthy();

        //http://localhost/auth/reset-password/df3c05ae46a9ab2d335f37777c690b21da23c3e63c4d5a24446e3b7814d86e5d
        let match = emails[0].body.match(/http.*auth\/reset-password\/([\d\w]*)/m);
        expect(match[1].length > 0).toBeTruthy();

        //check user can login
        await app_action.logout();

        // let meow1 = async (response) => {
        //     if (response.url().indexOf('api/v1') > 0) {
        //         console.log((await response.text()).toString());
        //     }
        // };
        // page.on('response', meow1);

    user_info.password = 'goodpassword';
    let url = app_action.convertBackendURLtoFrontendURL(match[0]);
    await page.goto(url);
    await reset_password_page.fillForm({email: user_info.email, password: user_info.password, password_confirmation: user_info.password});
    await reset_password_page.submitForm();
    await page.waitForSelector(':has-text("Password was resetted successfully")');

        await app_action.login(user_info.username, user_info.password);
        await page.waitForURL('**/announcements');
    });

    test('Edit Existing user', async ({ page }) => {
        await user_editor_page.goto({ id: 2 });
        await page.waitForSelector('input[type="submit"][value="Update"]');
        await user_editor_page.submitForm();
        await page.waitForSelector(':has-text("User was updated successfully")');
    });

    test('New user registers themselves', async ({ page }) => {
        await app_action.logout();
        await new_user_register_page.goto();
        let user_info = await new_user_register_page.fillForm();
        await new_user_register_page.submitForm();

        await page.waitForSelector(':has-text("User was created successfully")');
        let emails = await app_action.getSentEmails({ to: user_info.email });

        expect(emails.length > 0).toBeTruthy();

        await app_action.login(user_info.username, user_info.password);
        await page.waitForSelector(
            ':has-text("Your email is not verified. please check your email for verification email.")',
        );

        //http://localhost/email/verify/123/df3c05ae46a9ab2d335f37777c690b21da23c3e63c4d5a24446e3b7814d86e5d
        let match = emails[0].body.match(/http.*auth\/verify\-email\/(\d*)\/([\d\w\?\=\&]*)/m);
        expect(match[1].length > 0).toBeTruthy();
        let url = app_action.convertBackendURLtoFrontendURL(match[0]);
        //console.log(match[0]); //http://localhost/auth/verify-email/293/e1f1f49c67dd513857493165ce88ce564ad109ee
        await page.goto(url);
        await page.waitForSelector(':has-text("Email is now verified")');

        await app_action.login(user_info.username, user_info.password);
        await page.waitForURL('**/announcements');
    });

    test('test become feature', async ({ page }) => {
        await user_editor_page.goto({ id: 2 });
        await page.click('button:has-text("Impersonate")');
        await page.waitForURL('**/announcements');
        await page.waitForSelector(':has-text("Announcements")');

        await app_action.logout();
        await app_action.loginAsAdmin();
        await page.waitForSelector(':has-text("User Management")');
    });
});
