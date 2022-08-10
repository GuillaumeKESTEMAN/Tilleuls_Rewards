import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteInput, ReferenceInput} from "react-admin";
// @ts-ignore
import {MESSAGE_HELPER, MESSAGE_PLACE_HOLDER} from "../../config/components/lot.ts";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({lot}) => (
    <EditGuesser {...lot}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity"/>
        <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER} helperText={MESSAGE_HELPER}/>

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </EditGuesser>
);
