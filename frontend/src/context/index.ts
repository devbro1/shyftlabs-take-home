import React from 'react';
import ContainerComp from './container';
import { globalContextInitialValue } from './value';

// init the main context (equal to store in redux)
export const GlobalContext = React.createContext(globalContextInitialValue);
export { ContainerComp as ContextProviderComp };
