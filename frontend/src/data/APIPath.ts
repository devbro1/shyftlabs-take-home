type APIParam = string | number;

// api call urls list
// all backend used apis
export const __APIPath = {
    auth: {
        signIn: `${import.meta.env.VITE_APP_API}tokens/login`,
        signUp: `${import.meta.env.VITE_APP_API}users/register`,
        me: `${import.meta.env.VITE_APP_API}users/me`,
        forgetPassword: `${import.meta.env.VITE_APP_API}users/forgot_password`,
        resetPassword: `${import.meta.env.VITE_APP_API}users/reset_password`,
        logout: `${import.meta.env.VITE_APP_API}tokens/logout`,
        verifyEmail: (id: string, token: string) => {
            return `${import.meta.env.VITE_APP_API}emails/verify/${id}/${token}`;
        },
        impersonate: (id: number) => {
            return `${import.meta.env.VITE_APP_API}tokens/impersonate/${id}`;
        },
    },
    others: {
        unsplashRandom: `https://api.unsplash.com/photos/random?client_id=${
            import.meta.env.VITE_APP_UNSPLASH_CLIENT_ID
        }&query=car`,
        countries: (id?: APIParam) => `${import.meta.env.VITE_APP_API}countries/${id ? id : ''}`,
    },
    announcement: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}announcements/${id ? id : ''}`,
    },
    permission: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}permissions/${id ? id : ''}`,
    },
    translation: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}translations/${id ? id : ''}`,
        cached: (lang: string) => `${import.meta.env.VITE_APP_API}cached-translations/${lang}`,
        namespaces: () => `${import.meta.env.VITE_APP_API}translations/namespaces`,
        missing: (lang: string) => `${import.meta.env.VITE_APP_API}missing-translations/${lang}`,
    },
    role: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}roles/${id ? id : ''}`,
    },
    user: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}users/${id ? id : ''}`,
        appointments: (id: APIParam) => `${import.meta.env.VITE_APP_API}users/${id}/appointments`,
    },
    file: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}files/${id ? id : ''}`,
        post: () => `${import.meta.env.VITE_APP_API}files/`,
    },
    drug: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}drugs/${id ? id : ''}`,
    },
    noc: {
        index: `${import.meta.env.VITE_APP_API}noc`,
    },
    dpd: {
        index: `${import.meta.env.VITE_APP_API}dpd`,
    },
    database: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}database/${id ? id : ''}`,
    },
    changeRequest: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}change-requests/${id ? id : ''}`,
    },
    Processor: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}processors/${id ? id : ''}`,
    },
    export: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}exports/${id ? id : ''}`,
    },
    disorder: {
        index: (id?: APIParam) => `${import.meta.env.VITE_APP_API}disorders/${id ? id : ''}`,
    },
};
