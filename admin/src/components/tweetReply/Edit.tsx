import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {MESSAGE_PLACE_HOLDER, NAME_CHOICES} from "../../config/tweetReply.ts";
import {SelectInput} from "react-admin";

interface Props {
    tweetReply: TweetReply;
}

export const Edit: FunctionComponent<Props> = ({tweetReply}) => (
    <EditGuesser {...tweetReply}>
        <SelectInput source="name"
                     choices={NAME_CHOICES}
                     required
                     disabled
        />
        <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
    </EditGuesser>
);
