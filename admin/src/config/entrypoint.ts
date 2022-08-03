export const API_ENTRYPOINT =
    typeof window === 'undefined'
        ? process.env.NEXT_PUBLIC_ENTRYPOINT
        : 'https://localhost/api';

export const ENTRYPOINT = 'https://localhost';
