// import { HydraAdmin } from "@api-platform/admin";
import Head from "next/head";
import {Navigate, Route} from "react-router-dom";
import {
    CreateGuesser,
    fetchHydra as baseFetchHydra,
    hydraDataProvider as baseHydraDataProvider, ResourceGuesser,
    useIntrospection,
} from "@api-platform/admin";
import {FileField, FileInput} from "react-admin";
import {parseHydraDocumentation} from "@api-platform/api-doc-parser";
import authProvider from "./utils/authProvider.tsx";
import {ENTRYPOINT} from "./config/entrypoint.ts";


const getHeaders = () => localStorage.getItem("token") ? {
    Authorization: `Bearer ${localStorage.getItem("token")}`,
} : {};

const fetchHydra = (url, options = {}) =>
    baseFetchHydra(url, {
        ...options,
        headers: getHeaders,
    });
const NavigateToLogin = () => {
    const introspect = useIntrospection();

    if (localStorage.getItem("token")) {
        introspect();
        return <></>;
    }
    return <Navigate to="/login"/>;
};

const MediaObjectsCreate = props => (
    <CreateGuesser {...props}>
        <FileInput source="file" name="file">
            <FileField source="src" title="media_object"/>
        </FileInput>
    </CreateGuesser>
);

const apiDocumentationParser = async () => {
    try {
        return await parseHydraDocumentation(ENTRYPOINT, {headers: getHeaders});
    } catch (result) {
        const {api, response, status} = result;
        if (status !== 401 || !response) {
            throw result;
        }

        // Prevent infinite loop if the token is expired
        localStorage.removeItem("token");

        return {
            api,
            response,
            status,
            customRoutes: [
                <Route key="/" path="/" component={NavigateToLogin}/>
            ],
        };
    }
};

const dataProvider = baseHydraDataProvider({
    entrypoint: ENTRYPOINT,
    httpClient: fetchHydra,
    apiDocumentationParser,
});

const AdminLoader = () => {
    if (typeof window !== "undefined") {
        const {HydraAdmin} = require("@api-platform/admin");
        return (<HydraAdmin dataProvider={dataProvider} authProvider={authProvider} entrypoint={ENTRYPOINT}>
            <ResourceGuesser name="lots" />
            <ResourceGuesser name="rewards" />
            <ResourceGuesser name="players" />
            <ResourceGuesser name="media_objects" create={MediaObjectsCreate}/>
        </HydraAdmin>);
    }

    return <></>;
};

const Admin = () => (
    <>
        <Head>
            <title>API Platform Admin</title>
        </Head>

        <AdminLoader/>
    </>
);
export default Admin;