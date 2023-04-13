export const __PaginationStyles = {
    button: (isActive?: boolean) =>
        `${
            isActive
                ? 'bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
        } relative flex items-center px-4 py-2 border text-sm font-medium`,
    space: 'px-4 py-2',
    select: 'border-gray-300 text-gray-500 relative flex items-center px-4 py-2 border text-sm font-medium',
};
