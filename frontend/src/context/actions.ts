import { AppContextActionKeyEnum, AppContextActionType, AppContextType } from '../types';

// helper function for container.tsx
// check type and set given value to exact field of state in order to given type
export function globalStateSetter(e: AppContextActionType[], state: AppContextType): AppContextType {
    const newState: AppContextType = { ...state };
    for (const action of e) {
        switch (action.key) {
            case AppContextActionKeyEnum.darkMode:
                newState.darkMode = action.value;
                break;
            case AppContextActionKeyEnum.showSideBar:
                newState.showSideBar = action.value;
                break;
            case AppContextActionKeyEnum.user:
                newState.user = action.value;
                break;
            case AppContextActionKeyEnum.authStatus:
                newState.authStatus = action.value;
                break;
            case AppContextActionKeyEnum.accessToken:
                newState.accessToken = action.value;
                break;
            case AppContextActionKeyEnum.reactFlowIsDraggingFrom:
                newState.reactFlowIsDraggingFrom = action.value;
                break;
            default:
                break;
        }
    }
    return newState;
}

export function canUser(perms: any, context: AppContextType, any = false): boolean {
    if (!context.user) {
        return false;
    }

    if (Array.isArray(perms)) {
        const intersect = perms.filter((obj: any) => {
            return context.user?.all_permissions.includes(obj);
        });

        if (any && intersect.length === 0) {
            return false;
        } else if (!any && intersect.length != perms.length) {
            return false;
        }
    } else {
        return context.user?.all_permissions.includes(perms);
    }

    return true;
}
