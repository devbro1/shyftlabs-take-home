export interface __ResponseType<T> {
    status: number;
    data: T;
}

export interface __PaginationType<T> {
    current_page: number;
    data: T[];
    total: number;
    per_page: number;
    links: T[];
}

export enum __AuthStatusEnum {
    valid = 'VALID',
    invalid = 'INVALID',
    pending = 'PENDING',
}
