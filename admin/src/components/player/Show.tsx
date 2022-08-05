import {FunctionComponent} from "react";
import {Player} from "../../types/Player";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

interface Props {
    player: Player;
}

export const Show: FunctionComponent<Props> = ({player}) => {
    return (
        <ShowGuesser {...player}>
            <FieldGuesser source="name"/>
            <FieldGuesser source="username"/>
            <FieldGuesser source="lastPlayDate"/>
        </ShowGuesser>
    );
}
