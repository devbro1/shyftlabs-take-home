type routeParam = string | number;

// font end app router urls
// all url used in router structure and redirects
export const __RoutePath = {
    auth: {
        __index: '/auth',
        signIn: () => `${__RoutePath.auth.__index}/sign-in`,
        signUp: () => `${__RoutePath.auth.__index}/sign-up`,
        forgetPassword: () => `${__RoutePath.auth.__index}/forgot-password`,
        resetPassword: (token: routeParam) => `${__RoutePath.auth.__index}/reset-password/${token}`,
        verifyEmail: (id: routeParam, token: routeParam) => `${__RoutePath.auth.__index}/verify-email/${id}/${token}`,
    },
    announcement: {
        __index: `/announcements`,
        new: () => `${__RoutePath.announcement.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.announcement.__index}/${id}`,
    },
    permission: {
        __index: `/permissions`,
        new: () => `${__RoutePath.permission.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.permission.__index}/${id}`,
    },
    role: {
        __index: `/roles`,
        new: () => `${__RoutePath.role.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.role.__index}/${id}`,
    },
    user: {
        __index: `/users`,
        new: () => `${__RoutePath.user.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.user.__index}/${id}`,
    },
    translation: {
        __index: `/translations`,
        new: () => `${__RoutePath.translation.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.translation.__index}/${id}`,
    },
    drug: {
        __index: `/drugs`,
        import: `/drugs/import`,
        new: () => `/drugs/new`,
        edit: (day: any) => `/drugs/${day}`,
        audits: (day: any) => `/drugs/${day}/audits`,
        processor: () => `${__RoutePath.drug.__index}/processors/`,
    },
    healthCanada: {
        __index: `/health-canada`,
        noc: `/health-canada/noc`,
        dpd: `/health-canada/dpd`,
    },
    changeRequest: {
        __index: `/change-requests`,
        edit: (id: routeParam) => `${__RoutePath.changeRequest.__index}/${id}`,
        processor: () => `${__RoutePath.changeRequest.__index}/processors/`,
    },
    export: {
        __index: `/exports`,
        new: () => `${__RoutePath.export.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.export.__index}/${id}`,
    },
    disorder: {
        __index: `/disorders`,
        new: () => `${__RoutePath.disorder.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.disorder.__index}/${id}`,
    },
};
