import {FunctionComponent} from "react";
import {Player} from "../../types/Player";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {DateField} from "react-admin";

interface Props {
    player: Player;
}

export const Show: FunctionComponent<Props> = ({player}) => {
    return (
        <ShowGuesser {...player}>
            <FieldGuesser source="name"/>
            <FieldGuesser source="username"/>
            <DateField source="lastPlayDate"
                       showTime
                       locales="fr-FR"
                       options={{ weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric' }}/>
        </ShowGuesser>
    );
}
