export interface __AnnouncementType {
    title: string;
    body: string;
    updated_at: string;
    created_at: string;
    id: number;
}

export interface __PermissionType {
    created_at: string;
    description: string;
    guard_name: string;
    id: number;
    name: string;
    system: boolean;
    updated_at: string;
}

export interface __RoleType {
    created_at: string;
    permissions: __PermissionType[];
    description: string;
    guard_name: string;
    id: number;
    name: string;
    system: boolean;
    updated_at: string;
}

export interface __UserType {
    active: boolean;
    address?: string;
    available_permissions: __PermissionType[];
    available_roles: __RoleType[];
    city?: string;
    country_code: string;
    created_at?: string;
    email: string;
    email_verified_at: string;
    full_name: string;
    id: number;
    permissions: __PermissionType[];
    all_permissions: string[];
    phone1?: string;
    phone2?: string;
    postal_code?: string;
    province_code?: string;
    roles: __RoleType[];
    updated_at: string;
    username: string;
}

export interface __ServiceType {
    active: boolean;
    id: number;
    name: string;
    workflow_id: number;
}

export interface __ServiceAvailablityType {
    id: number;
    service_id: number;
    store_id: number;
    workflow_id: number;
    company_id: number;
}

export interface __StoreType {
    active: boolean;
    address?: string;
    city?: string;
    country_code: string;
    created_at: string;
    id: number;
    latitude?: number;
    longitude?: number;
    name: string;
    postal_code?: string;
    province_code?: string;
    store_no?: string;
    updated_at?: string;
}

export interface __AnnouncementType {
    title: string;
    body: string;
    updated_at: string;
    created_at: string;
    id: number;
}

export interface __WorkflowType {
    active: boolean;
    description: string;
    edges?: __WorkflowEdgeType[];
    nodes?: __WorkflowNodeType[];
    id: number;
    name: string;
    flow?: any;
}

export interface __WorkflowEdgeType {
    id: number;
    name: string;
    source_id: number;
    target_id: number;
    workflow_id: number;
}

export interface __WorkflowNodeType {
    id: number;
    label: string;
    position_x: number;
    position_y: number;
    type: __WorkflowNodeEnum;
    workflow_id: number;
}

export enum __WorkflowNodeEnum {
    input = 'EditableNodeInput',
    default = 'EditableNodeDefault',
    output = 'EditableNodeOutput',
}

export interface __ActionType {
    action_variables?: any[];
    active: boolean;
    backend_uri: string;
    created_at: string;
    frontend_uri: string;
    id: number;
    name: string;
    updated_at: string;
    workflow_node_variables: any[];
}

export interface __LeadActionType {
    action: __ActionType;
    action_id: number;
    alternative_name: string;
    created_at: string;
    id: number;
    permission_id: number | undefined;
    updated_at: string;
    variables: any;
    workflow_node_id: number;
}

export interface __CustomerType {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone1: string;
    phone2: string;
    address: string;
    city: string;
    province_code: string;
    country_code: string;
    postal_code: string;
}

export interface __LeadType {
    id: number;
    service_id: number;
    workflow_id: number;
    store_id: number;
    status_id: number;
    status?: any;
    service?: any;
    store?: any;
    customer: __CustomerType;
    owners?: any;
    invoices?: any;
}

export interface __CompanyType {
    id: number;
    active: boolean;
    name: string;
    address: string;
    city: string;
    province_code: string;
    country_code: string;
    postal_code: string;
    owner_ids?: number[];
    employee_ids?: number[];
    owners: __UserType[];
    employees: __UserType[];
}

export interface __CompanyType {
    id: number;
    active: boolean;
    name: string;
    address: string;
    city: string;
    province_code: string;
    country_code: string;
    postal_code: string;
    owner_ids?: number[];
    employee_ids?: number[];
    owners: __UserType[];
    employees: __UserType[];
}

export interface __TranslationType {
    id: number;
    language: string;
    key: string;
    translation: string;
    namespace: string;
}

export interface AppointmentType {
    id: number;
    owner_id: number;
    created_by: number;
    dt_start: string;
    dt_end: string;
    services: number[];
    stores: number[];
}
