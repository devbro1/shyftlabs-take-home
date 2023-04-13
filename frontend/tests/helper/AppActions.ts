import { expect } from '@playwright/test';
import LoginPage from './pom/LoginPage';
import { Pool } from 'pg';
import Knex from 'knex';

const pg = Knex({
    client: 'pg',
    connection: process.env.PLAYWRIGHT_DB_CONNECTION,
    searchPath: ['knex', 'public'],
});

class AppActions
{
    page: any;

    constructor(page) {
        this.page = page;
    }

    async login(username,password)
    {
        var login_page = new LoginPage(this.page)
        await login_page.goto();
        await login_page.fillForm({username: username, password: password});
        await login_page.submitForm();
        // await Promise.any([
        //     this.page.waitForURL('announcements'),
        //     this.page.waitForURL('auth/email_is_not_verified'),
        // ])
    }

    async logout()
    {
        await this.page.click('[aria-label="Account"]');
        await this.page.click('text="Log out"');
    }

    async become(username)
    {
        var login_page = new LoginPage(this.page);
        login_page.goto();
        login_page.fillForm({username: 'farzad', password: 'password'});
        login_page.submitForm();

        //navigate to user list
        //find user
        //impersonate
    }

    addUser(user_info = {})
    {
        user_info
    }

    fillForm(data = {})
    {
        
    }

    async getLastEmail()
    {
        const credentials = {
            user: "postgres",
            host: "localhost",
            database: "practice_db_1",
            password: "postgres",
            port: 5432,
          };

        const pool = new Pool(credentials);
        const now = await pool.query('SELECT * from notification_logs order by id DESC limit 1');
        await pool.end();

        return now;
    }

    async getSentEmails(props)
    {
        let limit = props.limit || 1;
        delete props.limit;

        let promise = pg('notification_logs')
            .where(props)
            .orderBy('id', 'desc')
            .limit(limit);

        // promise.then((rows)=>{
        //     console.log(rows);

        //     return rows;
        // })
        // promise.catch((e)=>{
        //     console.log(e);
        // });
        //console.log(promise.toString());
        return promise;
    }

    async queryDB(table_name,conditions)
    {
        let promise = pg(table_name)
            .where(conditions);

        //console.log(promise.toString());
        return promise;
    }

    async truncateDB(table_name)
    {
        return pg(table_name).truncate();
    }

    async hasText(text)
    {
        var selector = ':has-text("' + text + '")';
        await this.page.waitForSelector(selector);
    }

    contains(text)
    {
        return this.hasText(text)
    }

    async loginAsAdmin()
    {
        this.page.context().clearPermissions();
        await this.login('farzad','password');
        await this.page.waitForURL('/announcements');
    }
};

export default AppActions;