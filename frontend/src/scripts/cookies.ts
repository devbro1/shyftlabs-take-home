import Cookies from 'universal-cookie';

const authenticationCookie = 'Authorization_token';

// set authentication token in cookies
function setAuth(token: string) {
    const options = {
        path: '/',
        // maxAge =
    };
    const cookies = new Cookies();
    cookies.set(authenticationCookie, token, options);
}

// check if authentication token exist in cookies
function checkAuth() {
    const cookies = new Cookies();
    if (cookies.get(authenticationCookie)) {
        return true;
    }
    return false;
}

// get the authentication token
function getAuth(): string | null {
    const cookies = new Cookies();
    const token = cookies.get(authenticationCookie);
    if (token) {
        return token;
    }
    return null;
}

function logout(): void {
    const cookies = new Cookies();
    cookies.remove(authenticationCookie);
}
export const __CookiesInterface = {
    setAuth,
    checkAuth,
    getAuth,
    logout,
};
