import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {MESSAGE_PLACE_HOLDER, NAME_CHOICES} from "../../config/tweetReply.ts";
import {SelectInput} from "react-admin";

interface Props {
    tweetReply: TweetReply;
}

export const Edit: FunctionComponent<Props> = ({tweetReply}) => {
    const toChoices = items => items.map(item => ({id: item, name: item}));

    // @ts-ignore
    return (
        <EditGuesser {...tweetReply}>
            <SelectInput source="name"
                         choices={toChoices(NAME_CHOICES)}
                         required
            />
            <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
        </EditGuesser>
    );
}
