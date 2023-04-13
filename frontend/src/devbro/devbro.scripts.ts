import { APIPath } from 'data';
import { CookiesInterface, RestAPI } from 'scripts';
import { AppContextActionKeyEnum, AppContextActionType, AppContextType, AuthStatusEnum, UserType } from 'types';

// check if user is authorized or not and if he is, get his profile
// called in base router (devbro.scripts.ts)
export function __checkUserAuthStatus(context: AppContextType) {
    const userAction: AppContextActionType = { key: AppContextActionKeyEnum.user, value: null };
    const authAction: AppContextActionType = { key: AppContextActionKeyEnum.authStatus, value: AuthStatusEnum.invalid };
    // if there is no token in cookies, no need to api call, he is definitely unauthorized.
    if (!CookiesInterface.checkAuth()) {
        context.update(authAction, userAction);
        return;
    }
    // if token is in the cookies, get user data and also check his token correctness.
    RestAPI.get<UserType>(APIPath.auth.me)
        .then(({ data }) => {
            userAction.value = data;
            authAction.value = AuthStatusEnum.valid;
        })
        .finally(() => {
            context.update(authAction, userAction);
        });
}
