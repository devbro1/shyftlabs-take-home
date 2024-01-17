import { ContextProviderComp } from './context';
import PagesComp from 'pages/pages.index';
import React, { Suspense, useEffect } from 'react';
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
            queries: { refetchOnWindowFocus: false, staleTime: 30 * 60 * 1000, networkMode: 'always' },
            mutations: {
                networkMode: 'always',
            },
        },
    });

    useEffect(() => {
        document.title = import.meta.env.VITE_APP_NAME || 'Meow Base App';
    }, []);

    return (
        <ContextProviderComp>
            <QueryClientProvider client={queryClient}>
                <ToastContainer
                    position="top-center"
                    autoClose={parseInt(import.meta.env.VITE_APP_NOTIFICATION_TIMEOUT as string)}
                    hideProgressBar={false}
                    newestOnTop={false}
                    closeOnClick
                    rtl={false}
                    pauseOnFocusLoss
                    draggable
                    pauseOnHover
                />
                <BrowserRouter>
                    <Suspense fallback="loading...">
                        <PagesComp />
                    </Suspense>
                </BrowserRouter>
            </QueryClientProvider>
        </ContextProviderComp>
    );
};

export default App;
