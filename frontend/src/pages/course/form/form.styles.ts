export const __AnnouncementFormStyle = {
    root: 'flex flex-col w-full min-h-screen p-4',
    row: 'flex flex-col md:flex-row mb-6',
    form: 'w-full lg:w-4/12 px-6 shadow-lg rounded-lg bg-gray-300 flex flex-col py-10 items-center',
    fields: (isLast?: boolean) => `flex-1 ${isLast ? '' : 'md:mr-6'}`,
    loading: 'flex items-center justify-center h-80',
    loadingIcon: 'animate-spin 5-x text-indigo-200',
    spacer: 'mb-6',
};
