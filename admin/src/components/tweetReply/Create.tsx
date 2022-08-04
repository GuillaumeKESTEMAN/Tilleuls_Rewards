import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {SelectInput} from "react-admin";
// @ts-ignore
import {NAME_CHOICES, MESSAGE_PLACE_HOLDER} from '../../config/tweetReply.ts';

interface Props {
    tweetReply: TweetReply;
}

export const Create: FunctionComponent<Props> = ({tweetReply}) => {
    const toChoices = items => items.map(item => ({id: item, name: item}));

    // @ts-ignore
    return (
        <CreateGuesser {...tweetReply}>
            <SelectInput source="name"
                         choices={toChoices(NAME_CHOICES)}
                         required
            />
            <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
        </CreateGuesser>
    );
}
