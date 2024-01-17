import { CookiesInterface } from 'scripts';
import { ResponseType } from 'types';
import axios from 'axios';

// add authentication headers to other headers
function generateHeader(object: any = {}): any {
    const header: { [k: string]: any } = {};
    const cookie = CookiesInterface.getAuth();
    // add authentication header
    if (cookie) {
        header['Authorization'] = 'Bearer ' + cookie;
    }
    // add other headers
    for (const key of Object.keys(object)) {
        header[key] = object[key];
    }
    return header;
}

// delete request
function del<R>(url: string): Promise<ResponseType<R>> {
    let status: number;
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'DELETE',
            headers: generateHeader({ 'Content-Type': 'application/json' }),
        })
            .then(function (response) {
                status = response.status;
                return response.json();
            })
            .then(function (data) {
                if (responseValidator(status)) resolve({ data, status });
                else reject({ data, status });
            })
            .catch((/* err */) => {
                reject({ data: null, status });
            });
    });
}

// post request
function post<R>(url: string, body: any, headers = { 'Content-Type': 'application/json' }): Promise<ResponseType<R>> {
    return axios.post(url, body, { headers: generateHeader(headers) });

    // return new Promise((resolve, reject) => {
    //     fetch(url, {
    //         method: 'POST',
    //         headers: generateHeader({ 'Content-Type': 'application/json' }),
    //         body: JSON.stringify(body),
    //     })
    //         .then(function (response) {
    //             status = response.status;
    //             return response.json();
    //         })
    //         .then(function (data) {
    //             if (responseValidator(status)) resolve({ data, status });
    //             else reject({ data, status });
    //         })
    //         .catch((/* err */) => {
    //             reject({ data: null, status });
    //         });
    // });
}

// form request (not post, like html form submit)
function form<R>(url: string, body: any): Promise<ResponseType<R>> {
    let status: number;
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
            body: body,
            headers: generateHeader(),
        })
            .then(function (response) {
                status = response.status;
                return response.json();
            })
            .then(function (data) {
                if (responseValidator(status)) resolve({ data, status });
                else reject({ data, status });
            })
            .catch((/* err */) => {
                reject({ data: null, status });
            });
    });
}

// put request
function put<R>(url: string, body: any): Promise<ResponseType<R>> {
    return axios.put(url, body, { headers: generateHeader({ 'Content-Type': 'application/json' }) });

    // let status: number;
    // return new Promise((resolve, reject) => {
    //     fetch(url, {
    //         method: 'PUT',
    //         body: JSON.stringify(body),
    //         headers: generateHeader({ 'Content-Type': 'application/json' }),
    //     })
    //         .then(function (response) {
    //             status = response.status;
    //             return response.json();
    //         })
    //         .then(function (data) {
    //             if (responseValidator(status)) resolve({ data, status });
    //             else reject({ data, status });
    //         })
    //         .catch((/* err */) => {
    //             reject({ data: null, status });
    //         });
    // });
}

// patch request
function patch<R>(url: string, body: any): Promise<ResponseType<R>> {
    let status: number;
    return new Promise((resolve, reject) => {
        fetch(url, {
            method: 'PATCH',
            body: JSON.stringify(body),
            headers: generateHeader({ 'Content-Type': 'application/json' }),
        })
            .then(function (response) {
                status = response.status;
                return response.json();
            })
            .then(function (data) {
                if (responseValidator(status)) resolve({ data, status });
                else reject({ data, status });
            })
            .catch((/* err */) => {
                reject({ data: null, status });
            });
    });
}

// get request
function get<R>(url: string, params: { [k: string]: any } = {}): Promise<ResponseType<R>> {
    const generatedUrl = new URL(url);
    // add query parameters like filters or pagination parameters
    Object.keys(params).forEach((key) => {
        generatedUrl.searchParams.append(key, params[key]);
    });

    return axios.get(generatedUrl.href, {
        headers: generateHeader({ 'Content-Type': 'application/json' }),
    });
}

// validate if request was successful
function responseValidator(status: number): boolean {
    return status >= 200 && status < 300;
}

async function getFormOptions(url: string, value_field = 'id', text_field = 'name') {
    const response: any = await get(url);
    const rc: any = [];

    response.data.data.map((opt: any) => {
        rc.push({ value: opt[value_field], title: opt[text_field] });
    });

    return rc;
}

function getErrorMessage(field_title: string, rule: string, rule_params: any) {
    let rc = 'unknown error ' + rule;

    if (rule === 'generic') {
        rc = rule_params[0] || `${field_title} is not valid.`;
        //rc = rule_params[0];
    } else if (rule === '!isEmpty') {
        rc = '' + field_title + ' is required';
    } else if (rule === 'isEmail') {
        rc = '' + field_title + ' must be a valid email';
    } else if (rule === 'isInt') {
        if ((rule_params[0]?.gt || rule_params[0]?.min) && (rule_params[0]?.lt || rule_params[0]?.max)) {
            rc =
                '' +
                field_title +
                ' must be a whole number between ' +
                (rule_params[0]?.gt || rule_params[0]?.min) +
                ' and ' +
                (rule_params[0]?.lt || rule_params[0]?.max);
        } else if (rule_params[0]?.gt || rule_params[0]?.min) {
            rc =
                '' +
                field_title +
                ' must be a whole number greater than ' +
                (rule_params[0]?.gt || rule_params[0]?.min);
        } else if (rule_params[0]?.lt || rule_params[0]?.max) {
            rc = '' + field_title + ' must be a whole number less than ' + (rule_params[0]?.lt || rule_params[0]?.max);
        } else {
            rc = '' + field_title + ' must be a number';
        }
    } else if (['isFloat', 'isDecimal'].includes(rule)) {
        rc = '' + field_title + ' must be a decimal number';
    } else if (rule === 'Required') {
        rc = '' + field_title + ' is required.';
    } else if (rule === 'Unique') {
        rc = '' + field_title + ' already exists.';
    } else if (rule === 'Min') {
        rc = '' + field_title + ' must be at least ' + rule_params[0] + ' characters long';
    } else if (rule === 'Max') {
        rc = '' + field_title + ' must be less than ' + rule_params[0] + ' characters long';
    } else if (rule === 'matchesField') {
        rc = '' + field_title + ' does not match ' + rule_params.title;
    } else if (rule === 'Exists') {
        rc = '' + field_title + ' does not exists in our system.';
    } else if (rule === 'LVR\\Phone\\Phone') {
        rc = '' + field_title + ' is not a valid phone number';
    } else if (rule === 'RequiredWith') {
        rc = '' + field_title + ' is required with fields ' + rule_params[0];
    } else if (rule === 'Digits') {
        rc = '' + field_title + ' must be ' + rule_params[0] + ' digits long';
    }

    return rc;
}

export const __RestAPI = {
    get,
    post,
    put,
    patch,
    form,
    delete: del,
    getFormOptions,
    getErrorMessage,
};
