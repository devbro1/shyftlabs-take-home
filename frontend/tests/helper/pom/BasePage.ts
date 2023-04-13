import _ from 'lodash';

class BasePage
{
    page: any;

    constructor(page) {
        this.page = page;
    }

    async goto(params = {})
    {
        await this.page.goto('/');
    }

    setDefaultFormValues(data = {})
    {
        return data;
    }

    async fillForm(data = {})
    {
        await this.page.waitForTimeout(3000);
        if(typeof this.getDefaultFormValueGenerators === 'function')
        {
            const base_data = this.getDefaultFormValueGenerators(data)

            for (const [key, value] of Object.entries(base_data)) {
                if (typeof data[key] === "undefined") {
                    data[key] = value();
                }
            }
        }
        else
        {
            data = this.setDefaultFormValues(data);
        }
        return await this.updateForm(data)
    }

    async updateForm(data = {})
    {
        for (const [key, value2] of Object.entries(data)) {
            let value = null;
            if(Object.prototype.toString.call(value2) === "[object Promise]")
            {
                value = await value2;
            }
            else
            {
                value = value2;
            }

            var selector = 'input[name=' + key + '][type=text]';
            if (await this.page.$(selector) != null) {
                await this.page.fill(selector, "" + value);
                continue;
            }

            selector = 'input[name=' + key + '][type=password]';
            if (await this.page.$(selector) != null) {
                await this.page.fill(selector, "" + value);
                continue;
            }

            selector = 'button[role=switch][name=' + key + ']';
            if (await this.page.$(selector) != null) {
                let field_value = await this.page.$eval(selector, el => el.value)
                field_value = (field_value === 'true');

                if(field_value != value && value)
                {
                    await this.page.click(selector);
                }
                else if(field_value != value && !value)
                {
                    await this.page.click(selector);
                }
                continue;
            }

            selector = 'select[name=' + key + ']';
            if (await this.page.$(selector) != null) {
                await this.page.selectOption(selector, value);
                continue;
            }

            selector = 'input[type=file][name=' + key + ']';
            if (await this.page.$(selector) != null) {
                await this.page.setInputFiles(selector, value);
                continue;
            }

            selector = '.multi-select-field.' + key + '';
            if (await this.page.$(selector) != null) {
                _.forEach(value,async (v,k)=>{
                    selector = '.multi-select-field.' + key + '';
                    await this.page.fill(selector + ' input.filter',v)
                    await this.page.click('.multi-select-field.' + key + ' li:has-text("' + v + '")')
                })
                continue;
            }

            //await this.page.screenshot({ path: 'screenshot.png' });
            console.log('could not fill ' + key + ' with ' + value);
        }

        return data;
    }

    async submitForm(selector = null)
    {
        if(selector === null) {
            await this.page.click('input[type=submit]');
        }
        else if (await this.page.$(selector) != null) {
            await this.page.click(selector);
        }
    }

    // choose a random value from a select element
    async selectRandomOption(selector = {field: 'field_name', skip_from_start: 0})
    {
        selector.skip_from_start = selector.skip_from_start || 0;

        if (await this.page.$('select[name=' + selector.field + ']') != null) {
            var options = await this.page.$$('select[name=' + selector.field + '] > option');
            var randomIndex = Math.floor(selector.skip_from_start + Math.random() * (options.length - selector.skip_from_start) );
            return options[randomIndex].getAttribute('value');
        }

        return '';
    }

    async filterTableRows(data: object)
    {
        await this.page.waitForTimeout(1000)
        for (const [k, v] of Object.entries(data)) {
            let selector = ".filter-container input[name=" + k + "]"
            if (await this.page.$(selector) != null) {
                await this.page.fill(selector, "" + v);
            }
        }
    }
};

export default BasePage;