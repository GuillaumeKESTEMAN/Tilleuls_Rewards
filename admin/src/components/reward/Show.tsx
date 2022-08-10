import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {ReferenceField} from "react-admin";

interface Props {
    reward: Reward;
}

export const Show: FunctionComponent<Props> = ({reward}) => {
    return (
        <ShowGuesser {...reward}>
            <ReferenceField source="lot" reference="lots" link="show">
                <FieldGuesser source="name"/>
            </ReferenceField>
            <ReferenceField source="game" reference="games" link="show">
                <ReferenceField source="player" reference="players" link="show">
                    <FieldGuesser source="username"/>
                </ReferenceField>
            </ReferenceField>
            <FieldGuesser source="distributed"/>
        </ShowGuesser>
    );
}
