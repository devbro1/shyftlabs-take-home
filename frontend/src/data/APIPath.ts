import _ from 'lodash';

type APIParam = string | number;

// api call urls list
// all backend used apis
export const __APIPath = {
    auth: {
        signIn: `${process.env.REACT_APP_API}tokens/login`,
        signUp: `${process.env.REACT_APP_API}users/register`,
        me: `${process.env.REACT_APP_API}users/me`,
        forgetPassword: `${process.env.REACT_APP_API}users/forgot_password`,
        resetPassword: `${process.env.REACT_APP_API}users/reset_password`,
        logout: `${process.env.REACT_APP_API}tokens/logout`,
        verifyEmail: (id: string, token: string) => {
            return `${process.env.REACT_APP_API}emails/verify/${id}/${token}`;
        },
        impersonate: (id: number) => {
            return `${process.env.REACT_APP_API}tokens/impersonate/${id}`;
        },
    },
    others: {
        unsplashRandom: `https://api.unsplash.com/photos/random?client_id=${process.env.REACT_APP_UNSPLASH_CLIENT_ID}&query=car`,
        countries: (id?: APIParam) => `${process.env.REACT_APP_API}countries/${id ? id : ''}`,
    },
    announcement: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}announcements/${id ? id : ''}`,
    },
    permission: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}permissions/${id ? id : ''}`,
    },
    translation: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}translations/${id ? id : ''}`,
        cached: (lang: string) => `${process.env.REACT_APP_API}cached-translations/${lang}`,
        namespaces: () => `${process.env.REACT_APP_API}translations/namespaces`,
    },
    role: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}roles/${id ? id : ''}`,
    },
    action: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}actions/${id ? id : ''}`,
    },
    user: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}users/${id ? id : ''}`,
        appointments: (id: APIParam) => `${process.env.REACT_APP_API}users/${id}/appointments`,
    },
    store: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}stores/${id ? id : ''}`,
    },
    service: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}services/${id ? id : ''}`,
        availabilities: (params: object = {}) => {
            const searchParams = new URLSearchParams();
            _.forEach(params, (value, key) => {
                searchParams.append(key, value);
            });
            if (_.size(params) > 0) {
                return `${process.env.REACT_APP_API}service-availabilities/?` + searchParams.toString();
            }

            return `${process.env.REACT_APP_API}service-availabilities/`;
        },
        availability: (id?: APIParam) => {
            return `${process.env.REACT_APP_API}service-availabilities/${id}`;
        },
    },
    company: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}companies/${id ? id : ''}`,
    },
    file: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}files/${id ? id : ''}`,
        post: () => `${process.env.REACT_APP_API}files/`,
    },
    lead: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}leads/${id ? id : ''}`,
        actions: (id?: APIParam, action_id: APIParam = '') =>
            `${process.env.REACT_APP_API}leads/${id ? id : ''}/actions/${action_id}`,
    },
    workflow: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}workflows/${id ? id : ''}`,
        node: (id?: APIParam, node_id?: APIParam) => `${process.env.REACT_APP_API}workflow-nodes/${node_id}`,
    },
    appointment: {
        index: (id?: APIParam) => `${process.env.REACT_APP_API}appointments/${id ? id : ''}`,
    },
};
