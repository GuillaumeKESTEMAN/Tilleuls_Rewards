import {FunctionComponent} from "react";
import {Player} from "../../types/Player";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {DateField, TextInput, BooleanInput, useListContext, useTranslate} from 'react-admin';

interface Props {
    players: Player[];
}

const PostFilterButton = ({ label }) => {
    const {setFilters, filterValues, displayedFilters} = useListContext();
    return (
        <BooleanInput
            source="lastPlayDate"
            label={label}
            onInput={(value) => {
                let newDisplayedFilters = displayedFilters;
                let newFilterValues = filterValues;

                if(value.target.checked) {
                    newDisplayedFilters = {...newDisplayedFilters, "exists[lastPlayDate]": true};
                    newFilterValues = {...newFilterValues, "exists[lastPlayDate]": true};
                } else {
                    delete newDisplayedFilters['exists[lastPlayDate]'];
                    delete newFilterValues['exists[lastPlayDate]'];
                }

                newDisplayedFilters = {...newDisplayedFilters, lastPlayDate: value.target.checked};
                newFilterValues = {...newFilterValues, lastPlayDate: value.target.checked};

                setFilters(newFilterValues, newDisplayedFilters);
            }}
        />
    );
};

// @ts-ignore
export const List: FunctionComponent<Props> = ({players}) => {
    const translate = useTranslate();

    const postFilters = [
        <PostFilterButton source="lastPlayDate" label={translate('resources.players.list.active_last_play_date')} alwaysOn/>,
        <TextInput source="name"/>,
        <TextInput source="username"/>,
    ];

    return (
        <ListGuesser rowClick="show" filters={postFilters} {...players}>
            <FieldGuesser source="name"/>
            <FieldGuesser source="username"/>
            <DateField source="lastPlayDate"
                       showTime
                       locales="fr-FR"
                       options={{
                           day: 'numeric',
                           month: 'numeric',
                           year: 'numeric',
                           hour: 'numeric',
                           minute: 'numeric'
                       }}/>
        </ListGuesser>
    );
}
