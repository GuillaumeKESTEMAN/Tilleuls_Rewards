import {FunctionComponent} from "react";
import {Reward} from "../../types/Reward";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {DateField, ReferenceField, useTranslate} from "react-admin";

interface Props {
    reward: Reward[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({reward}) => {
    const translate = useTranslate();
    return (
        <ListGuesser rowClick="show"
                     {...reward} >
            <ReferenceField source="lot" reference="lots" sortBy="lot.name" link="show">
                <FieldGuesser source="name"/>
            </ReferenceField>
            <ReferenceField label={translate('resources.players.name').split(' |||| ')[0]} sortBy="game.player.username" source="game"
                            reference="games" link="show">
                <ReferenceField source="player" reference="players" link="show">
                    <FieldGuesser source="username"/>
                </ReferenceField>
            </ReferenceField>
            <FieldGuesser source="distributed"/>
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
        </ListGuesser>
    );
}
