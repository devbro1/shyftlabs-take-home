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
