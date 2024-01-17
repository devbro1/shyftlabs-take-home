import _ from 'lodash';

class BasePage {
    page: any;

    constructor(page) {
        this.page = page;
    }

    async goto(params = {}) {
        await this.page.goto('/');
    }

    setDefaultFormValues(data = {}) {
        return data;
    }

    async fillForm(data = {}) {
        if (typeof this.getDefaultFormValueGenerators === 'function') {
            const base_data = this.getDefaultFormValueGenerators(data);

            for (const [key, value] of Object.entries(base_data)) {
                if (typeof data[key] === 'undefined') {
                    data[key] = value();
                }
            }
        } else {
            data = this.setDefaultFormValues(data);
        }
        return await this.updateForm(data);
    }

    async updateForm(data = {}) {
        await this.page.waitForTimeout(3000);
        function data_sorter(a, b) {
            if (a === 'country_code') {
                return -1;
            } else if (b === 'country_code') {
                return 1;
            }

            if (a == b) {
                return 0;
            }

            return a > b ? 1 : -1;
        }

        for (const [key, value2] of Object.entries(data).sort(data_sorter)) {
            let value: any = null;
            if (Object.prototype.toString.call(value2) === '[object Promise]') {
                value = await value2;
            } else {
                value = value2;
            }

            var selector = 'input[name="' + key + '"][type=text]';
            if ((await this.page.$(selector)) != null) {
                await this.page.fill(selector, '' + value);
                continue;
            }

            selector = 'input[name=' + key + '][type=password]';
            if ((await this.page.$(selector)) != null) {
                await this.page.fill(selector, '' + value);
                continue;
            }

            var selector = 'textarea[name=' + key + ']';
            if ((await this.page.$(selector)) != null) {
                await this.page.fill(selector, '' + value);
                continue;
            }

            selector = '.switch-field-name-' + key;
            if ((await this.page.$(selector)) != null) {
                //let field = await this.page.locator(selector);
                let field_value = (await this.page.$(selector + ' .switch-field-value-true')) != null;

                if (field_value != value && value) {
                    await this.page.click(selector);
                } else if (field_value != value && !value) {
                    await this.page.click(selector);
                }
                continue;
            }

            selector = 'select[name=' + key + ']';
            if ((await this.page.$(selector)) != null) {
                await this.page.selectOption(selector, value);
                continue;
            }

            selector = 'input[type=file][name=' + key + ']';
            if ((await this.page.$(selector)) != null) {
                await this.page.setInputFiles(selector, value);
                continue;
            }

            selector = '.multi-select-field.' + key + '';
            if ((await this.page.$(selector)) != null) {
                const field2 = await this.page.locator(selector);
                await field2.locator('div[class*="-container"]').click();
                const input2 = await field2.locator('div[class*="-container"] input[role=combobox]');

                for(let i=0;i<10;i++)
                {
                    await input2.press('Backspace');
                }
                for(const index in value) {
                    const v = value[index];
                    for (const c of v) {
                        await input2.press(c);
                    }
                    await input2.press('Tab');
                }

                continue;
            }

            console.log('could not fill ' + key + ' with ' + value);
        }

        return data;
    }

    async submitForm(selector: string | null = null) {
        if (selector === null) {
            await this.page.click('input[type=submit]');
        } else if ((await this.page.$(selector)) != null) {
            await this.page.click(selector);
        }
    }

    // choose a random value from a select element
    async selectRandomOption(selector = { field: 'field_name', skip_from_start: 0 }) {
        selector.skip_from_start = selector.skip_from_start || 0;

        if ((await this.page.$('select[name=' + selector.field + ']')) != null) {
            var options = await this.page.$$('select[name=' + selector.field + '] > option');
            var randomIndex = Math.floor(
                selector.skip_from_start + Math.random() * (options.length - selector.skip_from_start),
            );
            return options[randomIndex].getAttribute('value');
        }

        return '';
    }

    async filterTableRows(data: object) {
        await this.page.waitForTimeout(1000);
        for (const [k, v] of Object.entries(data)) {
            let selector = '.filter-container input[name=' + k + ']';
            await this.page.fill(selector, '' + v);
            // if ((await this.page.$(selector)) != null) {
                
            // }
        }
    }

    async scrollTo(selector) {
        const element = await this.page.locator(selector);
        await element.scrollIntoViewIfNeeded();
    }

    async getSelectInfo(selector: string) {
        let rc = {};
        const element = await this.page.locator(selector);
        let value = await element.evaluate((el) => {
            console.log(el);
            return el.value;
        });
        const option = await this.page.locator(selector + ' option[value="' + value + '"]');
        let label = await option.evaluate((el) => {
            console.log(el);
            return el.innerText;
        });

        return { value, label };
    }
}

export default BasePage;
