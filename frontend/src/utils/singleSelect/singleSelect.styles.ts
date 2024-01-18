export const MultiSelectStyles = {
    input: `flex w-full items-center`,
    box: (isInValid?: boolean) =>
        `flex items-center sm:text-sm rounded relative block w-full flex flex-col min-h-40 h-40 overflow-y-scroll bg-white ${
            isInValid ? 'border-red-400' : 'border-gray-300'
        } focus:outline-none focus:ring-indigo-500 border focus:border-indigo-500`,
    title: (error?: boolean) =>
        `block text-sm font-medium ${error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'}`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',
    item: 'rounded-t relative -mb-px block border p-4 border-grey hover:bg-gray-100 w-full',
};
