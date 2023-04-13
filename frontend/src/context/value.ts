import { AppContextType, AuthStatusEnum } from 'types';

// initial value of the global state in
export const globalContextInitialValue: AppContextType = {
    update: (...e) => {
        return 'this one is going to change in container.tsx' + e.length;
    },
    darkMode: false,
    showSideBar: true,
    user: null, // authorized user data
    authStatus: AuthStatusEnum.pending, // status authorization status
    accessToken: '',
    reactFlowIsDraggingFrom: false,
};
