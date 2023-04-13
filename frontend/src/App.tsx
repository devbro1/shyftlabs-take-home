import { ContextProviderComp } from './context';
import DevbroComp from 'devbro/devbro.index';
import React, { Suspense } from 'react';
import { BrowserRouter } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import './App.css';
import 'react-toastify/dist/ReactToastify.css';
//import { history } from 'data/browserhistory';

// root component that implement global components around the hole project (component tree)
const App: React.FC = () => {
    const queryClient = new QueryClient({
        defaultOptions: {
            queries: { refetchOnWindowFocus: false, staleTime: 30 * 60 * 1000 },

            mutations: {
                // mutation options
            },
        },
    });

    return (
        <ContextProviderComp>
            <QueryClientProvider client={queryClient}>
                <ToastContainer
                    position="top-center"
                    autoClose={parseInt(process.env.REACT_APP_NOTIFICATION_TIMEOUT as string)}
                    hideProgressBar={false}
                    newestOnTop={false}
                    closeOnClick
                    rtl={false}
                    pauseOnFocusLoss
                    draggable
                    pauseOnHover
                />
                <BrowserRouter>
                    <Suspense fallback="loading">
                        <DevbroComp />
                    </Suspense>
                </BrowserRouter>
            </QueryClientProvider>
        </ContextProviderComp>
    );
};

export default App;
