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

export interface DrugType {
    id: number;
    created_at: string;
    updated_at: string;
    ref_id: string;
    modified: string;
    alert_notifications: string;
    din: string;
    pin: string;
    din_pin: string;
    drug_or_product_name: string;
    din_duplicate: string;
    health_canada_drug_name: string;
    reformulary: string;
    rg_tier: string;
    conditions: string;
    drug_product_type: string;
    medical_condition: string;
    sub_medical_condition: string;
    drug_class: string;
    active_ingredient: string;
    gf_period_code: string;
    gf_period: string;
    step_therapy: string;
    quantity_limits: string;
    action: string;
    explanation: string;
    slf: string;
    slf_tier: string;
    slf_action: string;
    slf_gf_period: string;
    slf_ql: string;
    slf_gf_period_code: string;
    slf_screencode: string;
    slf_visible_on_website: string;
    slf_rationale_code: string;
    clic: string;
    clic_tier: string;
    clic_action: string;
    clic_gf_period: string;
    clic_targetted_letter: string;
    clic_ql: string;
    include_exclude: string;
    sa: string;
    clic_gf_period_code: string;
    clic_screencode: string;
    clic_visible_on_website: string;
    clic_rationale_code: string;
    gwl: string;
    gwl_tier: string;
    gwl_action: string;
    gwl_gf_period_code: string;
    gwl_screencode: string;
    gwl_visible_on_website: string;
    gwl_rationale_code: string;
    gwl_cada: string;
    gwl_cada_tier: string;
    gwl_cada_action: string;
    gwl_cada_screencode: string;
    gwl_cada_visible_on_website: string;
    gwl_cada_rationale_code: string;
    cs: string;
    cs_tier: string;
    cs_action: string;
    cs_gf_period: string;
    cs_gf_period_code: string;
    cs_screencode: string;
    cs_visible_on_website: string;
    cs_rationale_code: string;
    cs_notes: string;
    cs_iw: string;
    cs_iw_tier: string;
    cs_iw_action: string;
    cs_iw_gf_period: string;
    cs_iw_gf_period_code: string;
    cs_iw_screencode: string;
    cs_iw_visible_on_website: string;
    cs_iw_rationale_code: string;
    jg: string;
    jg_tier: string;
    jg_sa: string;
    jg_notes: string;
    jg_action: string;
    jg_explanation: string;
    jg_gf_period: string;
    jg_ql: string;
    jg_gf_period_code: string;
    jg_screencode: string;
    jg_visible_on_website: string;
    jg_rationale_code: string;
    generic_name: string;
    generic_version_of: string;
    screen_code: string;
    newscreencode_1: string;
    visible_on_website: string;
    blue_box: string;
    alternative_dins: string;
    alternative_dins_non_prescribed: string;
    strength: string;
    form: string;
    route_of_administration: string;
    drug_sub_type: string;
    manufacturer: string;
    discontinued_date: string;
    notes: string;
    ramq: string;
    ahfs: string;
    dc: string;
    schedule: string;
    a_m: string;
    tc: string;
    life_sustaining_otc: string;
    life_style_drug: string;
    name_or_product_name_french: string;
    reformulary_position_french: string;
    medical_condition_french: string;
    sub_medical_condition_french: string;
    drug_class_french: string;
    active_ingredient_french: string;
    generic_name_french: string;
    generic_version_of_french: string;
    blue_box_french: string;
    alternative_dins_non_prescribed_french: string;
    strength_french: string;
    form_french: string;
    test_strip: string;
    pin_slf: string;
    pin_clic: string;
    gx_available_on_the_market: string;
    notes_on_rg_select: string;
    vaccines_used_to_protect_against: string;
    vaccines_used_to_protect_against_french: string;
    rationale_code: string;
    rationale: string;
    rationale_french: string;
    quantity_limits_days: string;
    used_for_code: string;
    specialty_drug: string;
    slfu: string;
    prexdu: string;
    patient_support_program: string;
    special_distribution_program: string;
}

export interface ChangeReuqest {
    id: number;
    created_at: string;
    updated_at: string;
    drug_id: string;
    changes: any;
    source: string;
    started_by_id: number;
    status: string;
    viewed: boolean;
    message: string;
    drug?: DrugType;
}

export type Export = {
    id: number;
    name: string;
    created_at: string;
    updaded_at: string;
    status: string;
    message: string;
    file_id?: number;
};

export type Disorder = {
    id: number;
    name: string;
    category: string;
    used_for_code: string;
};

export type SelectOption = {
    value: string;
    title: string;
};
