export const ENTRYPOINT =
    typeof window === 'undefined'
        ? process.env.NEXT_PUBLIC_ENTRYPOINT
        : 'https://localhost:8000/api';