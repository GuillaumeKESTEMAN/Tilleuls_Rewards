import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {MESSAGE_PLACE_HOLDER, MESSAGE_HELPER} from '../../config/components/lot.ts';
import {AutocompleteInput, ReferenceInput} from "react-admin";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({lot}) => (
    <CreateGuesser {...lot}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity" defaultValue={0}/>
        <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER} helperText={MESSAGE_HELPER}/>

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </CreateGuesser>
);
