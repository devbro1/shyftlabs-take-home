import { test, expect } from '@playwright/test';
import LoginPage from './helper/pom/LoginPage';
import AppActions from './helper/AppActions';
import ResetPasswordPage from './helper/pom/ResetPasswordPage';
import ForgotPasswordPage from './helper/pom/ForgotPasswordPage';
import 'dotenv/config';

var app_action;
var login_page;
var reset_password_page;
var forgot_password_page;
test.describe('Login Page: ', () => {
  test.beforeEach(async ({ page }) => {
    // Go to the starting url before each test.
    login_page = new LoginPage(page);
    app_action = new AppActions(page);
    forgot_password_page = new ForgotPasswordPage(page);
    reset_password_page = new ResetPasswordPage(page);

    await page.goto('/');
  });

  test('can login using correct username/password', async ({ page }) => {
    await login_page.fillForm({username: 'farzad', password: 'password'});
    await login_page.submitForm();
    await page.waitForEvent('framenavigated',{timeout:5000});
  });


  test('can login using correct email/password', async ({ page }) => {
    await login_page.fillForm({username: 'farzadk@gmail.com', password: 'password'});
    await login_page.submitForm();
    await page.waitForEvent('framenavigated');
  });


  test('cannot login using incorrect username/password', async ({ page }) => {
    await login_page.fillForm({username: 'farzad', password: 'badpassword'});
    await login_page.submitForm();
    await page.locator('has-text("Invalid username or password")');

    await login_page.fillForm({username: 'farzadk@gmail.com', password: 'badpassword'});
    await login_page.submitForm();
    
    await page.locator('has-text("Invalid username or password")');
  });


  test('check validations and required fields', async ({ page }) => {
    await login_page.submitForm();
    await page.locator('has-text("invalid_request")');
  });

  test('user can logout successfully', async ({ page }) => {
    await app_action.login('farzad','password');
    await app_action.logout();
    await page.waitForURL('auth/sign-in');
  });

  test('user can request reset password', async ({ page }) => {
    await app_action.truncateDB('password_resets');
    await page.goto('/');
    await page.click('a:has-text("Forgot Password?")');
    await page.waitForURL('auth/forgot-password');
    await page.fill('input[type=text][name=email]','farzadk@gmail.com');
    await forgot_password_page.submitForm();
    await page.waitForSelector('text="Reset link sent to your email."');

    // await forgot_password_page.submitForm();
    // await page.waitForSelector('form > div > div:has-text("Unable to send reset link")');

    let rows = await app_action.queryDB('password_resets', {email: 'farzadk@gmail.com'});
    expect(rows.length == 1).toBeTruthy();
  });

});