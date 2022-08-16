import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {EditGuesser, FieldGuesser, InputGuesser} from "@api-platform/admin";
import {AutocompleteInput, ReferenceField, ReferenceInput, DateInput, useTranslate} from "react-admin";

interface Props {
    reward: Reward;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({reward}) => {
    const translate = useTranslate();
    return (
        <EditGuesser {...reward}>
            <ReferenceInput source="lot" reference="lots">
                <AutocompleteInput optionText="name" disabled/>
            </ReferenceInput>
            <ReferenceField label={translate('resources.players.name').split(' |||| ')[0]} source="game" reference="games" link={false}>
                <ReferenceInput source="player" reference="players">
                    <AutocompleteInput optionText="username" disabled/>
                </ReferenceInput>
            </ReferenceField>
            <ReferenceField label={translate('resources.games.fields.playDate')} source="game" reference="games" sortBy="game.playDate" link={false}>
                <DateInput source="playDate" disabled/>
            </ReferenceField>
            <InputGuesser source="distributed"/>
        </EditGuesser>
    );
}
