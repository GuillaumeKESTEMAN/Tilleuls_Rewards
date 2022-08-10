import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    reward: Reward[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({reward}) => (
    <ListGuesser rowClick="show" {...reward} >
        <ReferenceField source="lot" reference="lots" link="show">
            <FieldGuesser source="name"/>
        </ReferenceField>
        <ReferenceField source="game" reference="games" link="show">
            <ReferenceField source="player" reference="players" link="show">
                <FieldGuesser source="username"/>
            </ReferenceField>
        </ReferenceField>
        <FieldGuesser source="distributed"/>
    </ListGuesser>
);
