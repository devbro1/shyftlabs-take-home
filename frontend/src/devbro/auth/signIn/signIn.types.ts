import { UserType } from 'types';

// login api call response type that only used in this component
export interface __SingInAPIResponse {
    access_token: string;
    user: UserType;
}
