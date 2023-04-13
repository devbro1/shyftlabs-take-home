export const __Styles = {
    input: (error?: boolean) =>
        `appearance-none rounded relative block w-full px-3 py-2 border ${
            error
                ? 'border-red-400 placeholder-red-500 text-red-900'
                : 'border-gray-300 placeholder-gray-500 text-gray-900'
        } focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm`,

    title: (error?: boolean) =>
        `block text-sm font-medium ${error ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-100'}`,
    error: 'text-xs text-red-600 dark:text-red-400',
    description: 'block text-xs dark:text-gray-100',

    calendar: 'absolute border border-indigo-600 z-20 bg-white',
    hour_ul: 'list-reset text-center h-56 overflow-y-auto w-20',
    hour_il: 'h-8 flex content-center justify-items-center items-center p-0 pl-2 pr-2',
    hour_il_selected: 'h-8 flex content-center justify-items-center items-center p-0 pl-2 pr-2 bg-blue-300',
    hour_div: 'flex flex-row border-t-2 border-b-2 border-gray-100',
    header_tile: 'p-2',
    header_calendar: 'flex flex-row border-b-2 border-r-2 border-gray-100',
    calendar_div: 'border-b-2 border-r-2 border-gray-100',
    time_div: '',
    date_td: 'h-8 text-center text-black',
    date_td_today: 'h-8 text-center text-black border-b-2 border-yellow-900',
    date_td_selected: 'h-8 text-center text-black bg-blue-300',
    date_td_not_this_month: 'h-8 bg-white text-center text-gray-300',
    header_time: '',
    notificationContent:
        'absolute w-56 p-2 mt-2 text-gray-600 rounded-lg shadow-md min-w-max-content dark:text-gray-300 dark:bg-gray-700',
};
