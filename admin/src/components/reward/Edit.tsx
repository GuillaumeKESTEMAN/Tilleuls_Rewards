import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteInput, ReferenceField, ReferenceInput} from "react-admin";

interface Props {
    reward: Reward;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({reward}) => (
    <EditGuesser {...reward}>
        <ReferenceInput source="lot" reference="lots">
            <AutocompleteInput optionText="name" disabled/>
        </ReferenceInput>
        <ReferenceField source="game" reference="games">
            <ReferenceInput source="player" reference="players">
                <AutocompleteInput optionText="username" disabled/>
            </ReferenceInput>
        </ReferenceField>
        <InputGuesser source="distributed"/>
    </EditGuesser>
);
