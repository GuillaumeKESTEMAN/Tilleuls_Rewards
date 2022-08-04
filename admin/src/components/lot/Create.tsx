import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {MESSAGE_PLACE_HOLDER} from '../../config/lot.ts';
import {AutocompleteInput, ReferenceInput} from "react-admin";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({lot}) => (
    <CreateGuesser {...lot}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity" defaultValue={0}/>
        <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput label="Image" optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </CreateGuesser>
);
