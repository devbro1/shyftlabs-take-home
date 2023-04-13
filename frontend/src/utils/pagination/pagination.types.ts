export interface __PaginationProps {
    className?: string;
    page?: number;
    total?: number;
    pageSize?: number;
    onChange?: (e: number) => void;
    onPageSizeChange?: (e: number) => void;
    options?: __PaginationPageSizeType[];
}

export interface __PaginationPageSizeType {
    title: string;
    value: number;
}
