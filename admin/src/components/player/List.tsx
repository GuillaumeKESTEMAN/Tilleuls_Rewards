import {FunctionComponent} from "react";
import {Player} from "../../types/Player";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";

interface Props {
    players: Player[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({players}) => (
    <ListGuesser rowClick="show" {...players}>
        <FieldGuesser source="name"/>
        <FieldGuesser source="username"/>
        <FieldGuesser source="lastPlayDate"/>
    </ListGuesser>
);
