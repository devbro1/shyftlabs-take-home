import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

import HttpApi from 'i18next-http-backend';
import LanguageDetector from 'i18next-browser-languagedetector';
// don't want to use this?
// have a look at the Quick start guide
// for passing in lng and translations on init

import { APIPath } from 'data';

const backendOptions = {
    loadPath: APIPath.translation.cached('{{lng}}/{{ns}}'), //{{lng}}|{{ns}}
    addPath: APIPath.translation.missing('{{lng}}/{{ns}}'), // path to send missing translations to
    // request: (options: any, url: any, payload: any, callback: any) => {
    //     try {
    //         RestAPI.get(url).then((res) => {
    //             callback(res.data);
    //         });
    //     } catch (e) {
    //         console.error(e);
    //         callback(null, {
    //             status: 500,
    //         });
    //     }
    // },
};

i18n
    // load translation using http -> see /public/locales (i.e. https://github.com/i18next/react-i18next/tree/master/example/react/public/locales)
    // learn more: https://github.com/i18next/i18next-http-backend
    // want your translations to be loaded from a professional CDN? => https://github.com/locize/react-tutorial#step-2---use-the-locize-cdn
    .use(HttpApi)
    // detect user language
    // learn more: https://github.com/i18next/i18next-browser-languageDetector
    .use(LanguageDetector)
    // pass the i18n instance to react-i18next.
    .use(initReactI18next)
    // init i18next
    // for all options read: https://www.i18next.com/overview/configuration-options
    .init({
        fallbackLng: 'en',
        debug: false,
        backend: backendOptions,
        saveMissing: true,
        returnNull: false,
        interpolation: {
            escapeValue: false, // not needed for react as it escapes by default
        },
    });

export default i18n;
