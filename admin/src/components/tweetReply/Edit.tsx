import {FunctionComponent} from "react";
import {TweetReply} from "../../types/TweetReply";
import {EditGuesser, InputGuesser} from "@api-platform/admin";
// @ts-ignore
import {MESSAGE_HELPER, NAME_CHOICES} from "../../config/tweetReply.ts";
import {SelectInput, useRecordContext} from "react-admin";

interface Props {
    tweetReply: TweetReply;
}

const MessageInput = () => {
    const record = useRecordContext();
    let placeholder = NAME_CHOICES.find(
        tweetReply => {
            return tweetReply.id === record.name;
        }
    )?.placeholder ?? '';

    return <InputGuesser source="message" multiline placeholder={placeholder} helperText={MESSAGE_HELPER}/>;
};

export const Edit: FunctionComponent<Props> = ({tweetReply}) => (
    <EditGuesser {...tweetReply}>
        <SelectInput label="A quoi ça sert ?"
                     source="name"
                     choices={NAME_CHOICES}
                     required
                     disabled
        />
        <MessageInput/>
    </EditGuesser>
);
