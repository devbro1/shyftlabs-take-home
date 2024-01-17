import { __AppContextType, __AppContextActionKeyEnum, __AppContextActionType } from './context';
import { __AuthStatusEnum, __PaginationType, __ResponseType } from './general';
import {
    __AnnouncementType,
    __UserType,
    __PermissionType,
    __RoleType,
    __ServiceType,
    __StoreType,
    __WorkflowType,
    __WorkflowEdgeType,
    __WorkflowNodeType,
    __WorkflowNodeEnum,
    __ActionType,
    __CustomerType,
    __LeadType,
    __CompanyType,
    __ServiceAvailablityType,
    __TranslationType,
    __LeadActionType,
} from './models';

export interface AppContextType extends __AppContextType {}
export type { __AppContextActionType as AppContextActionType };
export { __AppContextActionKeyEnum as AppContextActionKeyEnum };
export interface ResponseType<T> extends __ResponseType<T> {}
export interface UserType extends __UserType {}
export interface AnnouncementType extends __AnnouncementType {}
export interface PaginationType<T> extends __PaginationType<T> {}
export { __AuthStatusEnum as AuthStatusEnum };
export interface PermissionType extends __PermissionType {}
export interface RoleType extends __RoleType {}
export interface ActionType extends __ActionType {}
export interface LeadActionType extends __LeadActionType {}
export interface ServiceType extends __ServiceType {}
export interface ServiceAvailablityType extends __ServiceAvailablityType {}
export interface LeadType extends __LeadType {}
export interface CustomerType extends __CustomerType {}
export interface StoreType extends __StoreType {}
export interface WorkflowType extends __WorkflowType {}
export interface WorkflowEdgeType extends __WorkflowEdgeType {}
export interface WorkflowNodeType extends __WorkflowNodeType {}
export interface CompanyType extends __CompanyType {}
export interface TranslationType extends __TranslationType {}
export { __WorkflowNodeEnum as WorkflowNodeEnum };

export type SelectOption = {
    value: string;
    title: string;
};
