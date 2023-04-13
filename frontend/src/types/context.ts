import { __AuthStatusEnum } from './general';
import { __UserType } from './models';

// type of the global state in context
// you can expand this one and add new fields to it
export interface __AppContextType {
    update: (...e: __AppContextActionType[]) => void;
    darkMode: boolean;
    showSideBar: boolean;
    user: __UserType | null;
    authStatus: __AuthStatusEnum;
    accessToken: string | null;
    reactFlowIsDraggingFrom: boolean | string;
}

// type of update function of global state single argument
// you can expand this one and add a {key, value} per filed you add to global state
export type __AppContextActionType =
    | {
          key: __AppContextActionKeyEnum.showSideBar;
          value: boolean;
      }
    | {
          key: __AppContextActionKeyEnum.darkMode;
          value: boolean;
      }
    | {
          key: __AppContextActionKeyEnum.user;
          value: __UserType | null;
      }
    | {
          key: __AppContextActionKeyEnum.accessToken;
          value: string | null;
      }
    | {
          key: __AppContextActionKeyEnum.authStatus;
          value: __AuthStatusEnum;
      }
    | {
          key: __AppContextActionKeyEnum.reactFlowIsDraggingFrom;
          value: boolean | string;
      };

// keys of the update function argument in global state
// you can expand this one and add a unique key per filed you add to global state
export enum __AppContextActionKeyEnum {
    showSideBar = 'CONTEXT_SHOW_SIDE_BAR_ACTION',
    darkMode = 'CONTEXT_DARK_MODE_ACTION',
    user = 'CONTEXT_USER_ACTION',
    authStatus = 'CONTEXT_AUTH_STATUS_ACTION',
    accessToken = 'CONTEXT_ACCESS_TOKEN',
    reactFlowIsDraggingFrom = 'REACT_FLOW_IS_DRAGGING_FROM',
}
