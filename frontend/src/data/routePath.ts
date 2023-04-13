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
    service: {
        __index: `/services`,
        new: () => `${__RoutePath.service.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.service.__index}/${id}`,
        availability: (params: string | string[][] | Record<string, string> | URLSearchParams | undefined) => {
            const query = new URLSearchParams(params);

            return `${__RoutePath.service.__index}/service-availabilities?` + query.toString();
        },
    },
    lead: {
        __index: `/leads`,
        new: () => `${__RoutePath.lead.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.lead.__index}/${id}`,
        action: (id: routeParam, action: string) => `${__RoutePath.lead.__index}/${id}/actions/${action}`,
    },
    store: {
        __index: `/stores`,
        new: () => `${__RoutePath.store.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.store.__index}/${id}`,
    },
    company: {
        __index: `/companies`,
        new: () => `${__RoutePath.company.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.company.__index}/${id}`,
    },
    workflow: {
        __index: `/workflows`,
        new: () => `${__RoutePath.workflow.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.workflow.__index}/${id}`,
        editNode: (id: routeParam, action_id: routeParam) => `${__RoutePath.workflow.__index}/${id}/nodes/${action_id}`,
    },
    action: {
        __index: `/actions`,
        edit: (id: routeParam) => `${__RoutePath.action.__index}/${id}`,
    },
    translation: {
        __index: `/translations`,
        new: () => `${__RoutePath.translation.__index}/new`,
        edit: (id: routeParam) => `${__RoutePath.translation.__index}/${id}`,
    },
    appointment: {
        __index: `/appointments`,
        new: () => `/appointments/new`,
        days: (day: any) => `/appointments/days/${day}`,
        weeks: (week: any) => `/appointments/weeks/${week}`,
    },
};
