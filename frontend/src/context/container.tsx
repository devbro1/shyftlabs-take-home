import { GlobalContext } from './index';
import React, { useEffect, useRef, useState } from 'react';
import { AppContextActionType, AppContextType } from '../types';
import { globalStateSetter } from './actions';
import { globalContextInitialValue } from './value';

const ContainerComp: React.FC = (props: any) => {
    // define the context value ( the global state )
    // overriding the useless update function that defined in initial value and making it functional.
    const [globalState, setGlobalState] = useState<AppContextType>({
        ...globalContextInitialValue,
        update: updateGlobalContext,
    });
    const ref = useRef<AppContextType>(globalState);

    useEffect(() => {
        ref.current = globalState;
    }, [globalState]);
    // updating global state by helper function.
    function updateGlobalContext(...e: AppContextActionType[]) {
        setGlobalState({ ...globalStateSetter(e, ref.current) });
    }

    return <GlobalContext.Provider value={globalState}>{props.children}</GlobalContext.Provider>;
};

export default ContainerComp;
