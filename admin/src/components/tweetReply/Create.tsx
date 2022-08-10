import {FunctionComponent, useState} from "react";
import {TweetReply} from "../../types/TweetReply";
import {CreateGuesser, InputGuesser} from "@api-platform/admin";
import {SelectInput} from "react-admin";
// @ts-ignore
import {MESSAGE_HELPER, NAME_CHOICES} from '../../config/components/tweetReply.ts';

interface Props {
    tweetReply: TweetReply;
}

// @ts-ignore
export const Create: FunctionComponent<Props> = ({tweetReply}) => {
    const [placeholderMessage, setPlaceholderMessage] = useState("");

    return (
        <CreateGuesser {...tweetReply}>
            <SelectInput source="name"
                         choices={NAME_CHOICES}
                         onChange={value => {
                             let placeholder = NAME_CHOICES.find(
                                 tweetReply => {
                                     return tweetReply.id === value.target.value;
                                 }
                             )?.placeholder ?? '';
                             setPlaceholderMessage(placeholder);
                         }}
                         required
            />
            <InputGuesser source="message" multiline placeholder={placeholderMessage} helperText={MESSAGE_HELPER}/>
        </CreateGuesser>
    );
}
