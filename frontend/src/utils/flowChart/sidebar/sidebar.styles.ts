import { WorkflowNodeEnum } from 'types';

export const __sidebarStyles = {
    root: 'px-3 py-4 bg-white w-full     md:w-1/4',
    description: 'text-sm mb-4',
    element: (type: WorkflowNodeEnum) =>
        `p-1 cursor-grab rounded flex justify-center items-center border mb-2 ${
            type === WorkflowNodeEnum.input ? 'border-blue-500' : ''
        }${type === WorkflowNodeEnum.default ? 'border-gray-900' : ''}${
            type === WorkflowNodeEnum.output ? 'border-rose-500' : ''
        }`,
};
