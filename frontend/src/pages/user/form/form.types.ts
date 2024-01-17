// countries backend model type
export interface __CountryType {
    code: string;
    name: string;
    provinces: __ProvincesType[];
}

// provinces backend model type
export interface __ProvincesType {
    abbreviation: string;
    code: string;
    country_code: string;
    name: string;
}
