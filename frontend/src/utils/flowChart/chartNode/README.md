### ChartNodeComp

this component is flow chart nodes

id: string;
data: { label: string };
type: string;
selected: boolean;
sourcePosition: string;
targetPosition: string;
onNameEditingFinished: (e: string, id: string) => void;

#### props:

-   id: (required) node id which passed by flowchart module
-   data: (required) custom object including label to render nodes
-   type: (required) determine type of node (input, default, output) passed by workflow module
-   selected: (required) passed by workflow module
-   sourcePosition: (required) passed by workflow module
-   targetPosition: (required) passed by workflow module
-   onNameEditingFinished: (required) on edition end function passed by workflow custom node

#### Notes:

-   this component has handle edition state internally
