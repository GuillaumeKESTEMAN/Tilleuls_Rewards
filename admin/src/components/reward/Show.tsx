import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import {DateField, ReferenceField, useTranslate} from "react-admin";

interface Props {
    reward: Reward;
}

export const Show: FunctionComponent<Props> = ({reward}) => {
    const translate = useTranslate();
    return (
        <ShowGuesser {...reward}>
            <ReferenceField source="lot" reference="lots" link="show">
                <FieldGuesser source="name"/>
            </ReferenceField>
            <ReferenceField label={translate('resources.players.name').split(' |||| ')[0]} source="game" reference="games" link="show">
                <ReferenceField source="player" reference="players" link="show">
                    <FieldGuesser source="username"/>
                </ReferenceField>
            </ReferenceField>
            <ReferenceField label={translate('resources.games.fields.playDate')} source="game" reference="games" sortBy="game.playDate" link={false}>
                <DateField source="playDate"
                           showTime
                           locales="fr-FR"
                           options={{
                               day: 'numeric',
                               month: 'numeric',
                               year: 'numeric',
                               hour: 'numeric',
                               minute: 'numeric'
                           }}/>
            </ReferenceField>
            <FieldGuesser source="distributed"/>
        </ShowGuesser>
    );
}
