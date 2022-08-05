import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {SelectInput} from "react-admin";
// @ts-ignore
import {NAME_CHOICES, MESSAGE_PLACE_HOLDER} from '../../config/tweetReply.ts';

interface Props {
    tweetReply: TweetReply;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({tweetReply}) => (
    <CreateGuesser {...tweetReply}>
        <SelectInput source="name"
                     choices={NAME_CHOICES}
                     required
        />
        <InputGuesser source="message" multiline placeholder={MESSAGE_PLACE_HOLDER}/>
    </CreateGuesser>
);
