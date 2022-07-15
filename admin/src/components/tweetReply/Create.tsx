import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {SelectInput} from "react-admin";
import {NAME_PLACE_HOLDER, MESSAGE_PLACE_HOLDER} from '../../config/tweetReply.ts';

interface Props {
    tweetReply: TweetReply;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({tweetReply}) => {
    const toChoices = items => items.map(item => ({id: item, name: item}));

    return (
        <CreateGuesser {...tweetReply}>
            <SelectInput source="name"
                         choices={toChoices(NAME_PLACE_HOLDER)}
                         required
            />
            <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
        </CreateGuesser>
    );
}
