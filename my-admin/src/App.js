// import { HydraAdmin } from "@api-platform/admin";
import Head from "next/head";
import {Navigate, Route} from "react-router-dom";
import {
    CreateGuesser, EditGuesser,
    fetchHydra as baseFetchHydra, FieldGuesser,
    hydraDataProvider as baseHydraDataProvider, InputGuesser, ListGuesser, ResourceGuesser, ShowGuesser,
    useIntrospection,
} from "@api-platform/admin";
import {FileField, FileInput, ReferenceInput, AutocompleteInput, ReferenceField} from "react-admin";
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

const LotsList = props => (
    <ListGuesser {...props}>
        <FieldGuesser source="name" />
        <FieldGuesser source="quantity" />
        <FieldGuesser source="message" />
        <ReferenceField label="Image" source="image" reference="media_objects">
            <FieldGuesser source="name" addLabel={true} />
        </ReferenceField>
    </ListGuesser>
);

const LotsShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source="name" addLabel={true} />
        <FieldGuesser source="quantity" addLabel={true} />
        <FieldGuesser source="message" addLabel={true} />
        <ReferenceField label="Image" source="image" reference="media_objects">
            <FieldGuesser source="name" addLabel={true} />
        </ReferenceField>
    </ShowGuesser>
);

const LotsCreate = props => (
    <CreateGuesser {...props}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity" defaultValue={0}/>
        <InputGuesser source="message"/>

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput label="Image" optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </CreateGuesser>
);

const LotsEdit = props => (
    <EditGuesser {...props}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity"/>
        <InputGuesser source="message"/>

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput label="Image" optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </EditGuesser>
);

const MediaObjectsCreate = props => (
    <CreateGuesser {...props}>
        <InputGuesser source="name"/>
        <FileInput source="file" name="file">
            <FileField source="src" title="media_object"/>
        </FileInput>
    </CreateGuesser>
);

const MediaObjectsEdit = props => (
    <EditGuesser {...props}>
        <InputGuesser source="name"/>
    </EditGuesser>
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
            <ResourceGuesser name="lots" list={LotsList} show={LotsShow} create={LotsCreate} edit={LotsEdit}/>
            <ResourceGuesser name="rewards"/>
            <ResourceGuesser name="players"/>
            <ResourceGuesser name="media_objects" edit={MediaObjectsEdit} create={MediaObjectsCreate}/>
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