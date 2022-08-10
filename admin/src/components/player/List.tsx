import {FunctionComponent} from "react";
import {Player} from "../../types/Player";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import { DateField } from 'react-admin';

interface Props {
    players: Player[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({players}) => (
    <ListGuesser rowClick="show" {...players}>
        <FieldGuesser source="name"/>
        <FieldGuesser source="username"/>
        <DateField source="lastPlayDate"
                   showTime
                   locales="fr-FR"
                   options={{ day: 'numeric', month: 'numeric', year: 'numeric', hour: 'numeric', minute: 'numeric' }}/>
    </ListGuesser>
);
