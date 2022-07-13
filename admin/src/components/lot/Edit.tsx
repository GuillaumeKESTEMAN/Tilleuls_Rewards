import {FunctionComponent} from "react";
import {Lot} from "../../types/Lot";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteInput, ReferenceInput} from "react-admin";
import {LOT_PLACE_HOLDER} from "../../config/lotPlaceHolder.ts";

interface Props {
    lot: Lot;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({lot}) => (
    <EditGuesser {...lot}>
        <InputGuesser source="name"/>
        <InputGuesser source="quantity"/>
        <InputGuesser source="message" multiline placeholder={LOT_PLACE_HOLDER} />

        <ReferenceInput
            reference="media_objects"
            source="image"
        >
            <AutocompleteInput label="Image" optionText="name" filterToQuery={searchText => ({title: searchText})}/>
        </ReferenceInput>
    </EditGuesser>
);
