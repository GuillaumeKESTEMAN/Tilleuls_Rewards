import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
import {MESSAGE_PLACE_HOLDER, NAME_PLACE_HOLDER} from "../../config/tweetReply.ts";
import {SelectInput} from "react-admin";

interface Props {
    tweetReply: TweetReply;
}

// @ts-ignore
export const Edit: FunctionComponent<Props> = ({tweetReply}) => {
    const toChoices = items => items.map(item => ({id: item, name: item}));

    return (
        <EditGuesser {...tweetReply}>
            <SelectInput source="name"
                         choices={toChoices(NAME_PLACE_HOLDER)}
                         required
            />
            <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
        </EditGuesser>
    );
}
