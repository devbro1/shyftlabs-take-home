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
    student: {
        __index: `/students`,
        new: () => `/students/new`,
        edit: (day: any) => `/students/${day}`,
    },
    course: {
        __index: `/course`,
        new: () => `/course/new`,
        edit: (day: any) => `/course/${day}`,
    },
};
