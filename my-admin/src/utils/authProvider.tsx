import jwtDecode from 'jwt-decode';
// @ts-ignore
import { ENTRYPOINT } from '../config/entrypoint.ts';

export default {
    login: ({ username, password }) => {
        const request = new Request(`${ENTRYPOINT}/login`, {
            method: 'POST',
            body: JSON.stringify({ email: username, password }),
            headers: new Headers({ 'Content-Type': 'application/json' }),
        });
        return fetch(request)
            .then((response) => {
                if (response.status < 200 || response.status >= 300) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .then(({ token }) => {
                localStorage.setItem('token', token);
            });
    },
    logout: () => {
        localStorage.removeItem('token');
        return Promise.resolve();
    },
    checkAuth: () => {
        try {
            if (
                !localStorage.getItem('token') ||
                new Date().getTime() / 1000 >
                // @ts-ignore
                jwtDecode(localStorage.getItem('token'))?.exp
            ) {
                return Promise.reject();
            }
            return Promise.resolve();
        } catch (e) {
            // override possible jwtDecode error
            return Promise.reject();
        }
    },
    checkError: (err) => {
        if ([401, 403].includes(err?.status || err?.response?.status)) {
            localStorage.removeItem('token');
            return Promise.reject();
        }
        return Promise.resolve();
    },
    getPermissions: () => Promise.resolve(),
};